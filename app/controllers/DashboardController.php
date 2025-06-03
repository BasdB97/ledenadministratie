<?php

class DashboardController extends Controller
{

  private $familyModel;
  private $dashboardModel;
  private $familyMemberModel;

  public function __construct()
  {
    $this->familyModel = $this->model('Family');
    $this->dashboardModel = $this->model('Dashboard');
    $this->familyMemberModel = $this->model('FamilyMember');
  }

  public function index()
  {
    $bookyearId = $_SESSION['bookyearId'];
    // Haal alle families op
    $families = $this->familyModel->getAllFamilies();

    // Haal per familie alle familieleden op
    foreach ($families as $family) {
      $family->members = $this->familyMemberModel->getFamilyMembersByFamilyId($family->id);
      $family->totalContribution = 0;

      // Haal per familielid de openstaande contributie op en tel dit op bij het toaal bedrag.
      foreach ($family->members as $familyMember) {
        $contribution = $this->familyMemberModel->getMemberContribution($familyMember->id, $bookyearId);
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
