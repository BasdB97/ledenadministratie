<?php

/**
 * DashboardController
 * 
 * Controller voor het beheren van de dashboard
 * 
 * Functies:
 * - Alle familiesdetails weergeven
 * 
 */
class DashboardController extends Controller
{

  private $familyModel;
  private $familyMemberModel;
  private $bookyearId;

  public function __construct()
  {
    $this->familyModel = $this->model('Family');
    $this->familyMemberModel = $this->model('FamilyMember');
    $this->bookyearId = $_SESSION['bookyearId'];
  }

  public function index()
  {
    // Haal alle families op
    $families = $this->familyModel->getAllFamilies();

    // Haal per familie alle familieleden op
    foreach ($families as $family) {
      $family->members = $this->familyMemberModel->getFamilyMembersByFamilyId($family->id);

      // Haal per familielid de openstaande contributie op en tel dit op bij het totaal bedrag.
      $family->totalContribution = 0;
      foreach ($family->members as $familyMember) {
        $contribution = $this->familyMemberModel->getMemberContribution($familyMember->id, $this->bookyearId);
        $familyMember->contribution = $contribution->amount;
        // Tel alleen alles bij elkaar op als er contributie in de database staat. Om fouten te voorkomen
        if (!empty($familyMember->contribution)) {
          $family->totalContribution += (float)$familyMember->contribution;
        }
      }
    }

    // Sorteer families op totaal contributie aflopend
    usort($families, function ($a, $b) {
      return $b->totalContribution <=> $a->totalContribution;
    });

    $data = [
      'families' => $families,
    ];

    $this->view('dashboard/index', $data);
  }
}
