<?php

/**
 * ContributionsController
 * 
 * Controller voor het beheren van contributies
 * 
 * Functies:
 * - Contributies toevoegen
 * - Betaling verwerken
 * - Contributies verwijderen
 * - Familieleden selecteren
 * 
 */

class ContributionsController extends Controller
{
  private $bookyearModel;
  private $familyMemberModel;
  private $bookyearId;
  private $contributionModel;

  public function __construct()
  {
    $this->bookyearModel = $this->model('Bookyear');
    $this->familyMemberModel = $this->model('FamilyMember');
    $this->contributionModel = $this->model('Contribution');
    $this->bookyearId = $_SESSION['bookyearId'];
  }

  public function index()
  {
    $data = [
      'bookyearId' => $this->bookyearId,
      'bookyears' => $this->bookyearModel->getAllBookyears(),
      'familyMembers' => $this->familyMemberModel->getAllFamilyMembers($this->bookyearId)
    ];

    // Tel alle contributie van het boekjaar bij elkaar op
    foreach ($data['bookyears'] as $bookyear) {
      $total = $this->bookyearModel->getTotalContribution($bookyear->id);
      $bookyear->total_contribution = $total;
    }
    $this->view('contributions/index', $data);
  }

  public function addContribution($familyMemberId)
  {
    // Haal data van het familielid in het boekjaar op om weer te geven
    $data = [
      'familyMember' => $this->familyMemberModel->getMemberContribution($familyMemberId, $this->bookyearId),
      'bookyear' => $this->bookyearModel->getBookyearById($this->bookyearId),
    ];
    $this->view('contributions/addContribution', $data);
  }

  public function processPayment($familyMemberId)
  {
    // Haal data van het familielid in het boekjaar op om weer te gevens
    $familyMember = $this->familyMemberModel->getMemberContribution($familyMemberId, $this->bookyearId);
    $data = [
      'bookyear' => $this->bookyearModel->getBookyearById($this->bookyearId),
      'bookyearId' => $this->bookyearId,
      'familyMember' => $familyMember,
      'contribution_err' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $contributionAmount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

      // Bereken de resterende contributie
      $data['newContributionAmount'] = $familyMember->amount - $contributionAmount;

      // Controleer of het contributiebedrag geldig is en niet hoger dan de resterende contributie
      if ($contributionAmount === false) {
        flash('contribution_error', 'Ongeldige contributiebedrag', 'alert-danger');
      } else if ($contributionAmount > $familyMember->amount) {
        $data['contribution_err'] = 'Contributiebedrag is groter dan de resterende contributie';
        $this->view('contributions/addContribution', $data);
      } else {
        // Controleer of de contributie al in de database staat
        if ($this->contributionModel->contributionExists($familyMember->id, $this->bookyearId)) {
          // Er bestaat al een contributie voor deze familielid in dit boekjaar, dus we updaten deze
          if ($this->contributionModel->updateMemberContribution($familyMember->id, $this->bookyearId, $data['newContributionAmount'])) {
            flash('contribution_message', 'Contributie bijgewerkt', 'alert-success');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
          }
        } else {
          // Er bestaat geen contributie voor deze familielid in dit boekjaar, dus we voegen deze toe
          if ($this->contributionModel->addContribution($data)) {
            flash('contribution_message', 'Contributie toegevoegd', 'alert-success');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
          }
        }
      }
    } else {
      // Toont het formulier voor het toevoegen van de contributie
      $data = [
        'familyMember' => $this->familyMemberModel->getMemberContribution($familyMemberId, $this->bookyearId),
      ];
      $this->view('contributions/addContribution', $data);
    }
  }

  public function deleteContribution($familyMemberId)
  {
    if ($this->contributionModel->deleteContribution($familyMemberId)) {
      flash('contribution_message', 'Contributie succesvol afgelost', 'alert-success');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
  }

  public function selectFamilyMember($familyId)
  {
    // Haal alle familieleden op om weer te geven
    $familyMembers = $this->familyMemberModel->getFamilyMembersByFamilyId($familyId);

    // Haal de contributie op voor elke familielid en controleer of de contributie bestaat
    foreach ($familyMembers as $member) {
      $contribution = $this->familyMemberModel->getMemberContribution($member->id, $this->bookyearId);
      if ($contribution) {
        $member->contribution = $contribution->amount;
      } else {
        $member->contribution = 0;
      }
    }

    $data = [
      'familyMembers' => $familyMembers,
      'bookyearId' => $this->bookyearId,
      'bookyears' => $this->bookyearModel->getAllBookyears(),
    ];
    $this->view('contributions/selectFamilyMember', $data);
  }
}
