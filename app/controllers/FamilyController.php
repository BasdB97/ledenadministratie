<?php

/**
 * FamilyController
 * 
 * Controller voor het beheren van families
 * 
 * Functies:
 * - Alle families weergeven
 * - Familie toevoegen
 * - Familie bewerken
 * - Familie verwijderen
 * - Familie details bekijken
 * 
 */

class FamilyController extends Controller
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

    // Sorteer families op achternaam
    usort($families, function ($a, $b) {
      return strcmp($a->last_name, $b->last_name);
    });

    $data = [
      'families' => $families,
    ];
    $this->view('family/index', $data);
  }

  public function addFamily()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $this->retrieveFormData();

      $data = validateForm($data);

      // Controleer of het adres bestaat en of het adres al bestaat voor een andere familie
      if (!checkErrors($data) && $this->familyModel->checkAddressExists($data)) {
        $data['address_err'] = 'Er woont al een familie op dit adres.';
      }

      // Als er geen fouten zijn, voeg de familie toe
      if (!checkErrors($data)) {
        if ($this->familyModel->addFamily($data)) {
          flash('family_message', 'Familie succesvol toegevoegd!', 'alert-success');
          redirect('family/index');
        } else {
          flash('family_message', 'Er ging iets mis bij het toevoegen van de familie. Probeer het opnieuw.', 'alert-danger');
          $this->view('family/addFamily', $data);
        }
      } else {
        $this->view('family/addFamily', $data);
      }
    } else {
      $this->view('family/addFamily', $data = []);
    }
  }

  public function editFamily($familyId)
  {
    $family = $this->familyModel->getFamilyById($familyId);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $this->retrieveFormData($familyId);
      $data = validateForm($data);

      // Controleer of het adres bestaat en of het adres al bestaat voor een andere familie
      if (!checkErrors($data) && $this->familyModel->checkAddressExists($data)) {
        $data['address_err'] = 'Er woont al een familie op dit adres.';
      }

      // Als er geen fouten zijn, werk de familie bij
      if (!checkErrors($data)) {
        if ($this->familyModel->updateFamily($data)) {
          flash('family_message', 'Familie succesvol bijgewerkt.', 'alert-success');
          redirect('family/index');
        } else {
          flash('family_message', 'Er ging iets mis bij het bijwerken van de familie. Probeer het opnieuw.', 'alert-danger');
          $this->view('family/editFamily', $data);
        }
      } else {
        $this->view('family/editFamily', $data);
      }
    } else {

      // Data van familie om in het formulier te zetten
      $data = [
        'family_id' => $familyId,
        'family' => $family,
        'last_name' => $family->last_name,
        'street' => $family->street,
        'house_number' => $family->house_number,
        'postal_code' => $family->postal_code,
        'city' => $family->city,
        'country' => $family->country,
      ];
      $this->view('family/editFamily', $data);
    }
  }

  public function deleteFamily($familyId)
  {
    if ($this->familyModel->deleteFamily($familyId, $this->bookyearId)) {
      flash('family_message', 'Familie en bijbehorende gegevens succesvol verwijderd.', 'alert-success');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    } else {
      flash('family_message', 'Er ging iets mis bij het verwijderen van de familie. Probeer opnieuw.', 'alert-danger');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }

  public function listFamilyDetails($familyId)
  {
    $data = [
      'bookyearId' => $this->bookyearId,
      'family' => $this->familyModel->getFamilyById($familyId),
      'family_members' => $this->familyMemberModel->getFamilyMembersByFamilyId($familyId),
    ];

    // Bereken het totaalbedrag van de contributies 
    $data['family']->total_contribution = 0;
    foreach ($data['family_members'] as $familyMember) {
      $contribution = $this->familyMemberModel->getMemberContribution($familyMember->id, $this->bookyearId);
      // Als er contributie is, sla dit op, als er geen contributie is, sla 0 op
      $familyMember->contribution = $contribution ? $contribution->amount : 0;
      // Tel het totaalbedrag van de contributies op
      $data['family']->total_contribution += $familyMember->contribution;

      // Haal het type van het familielid op
      $familyMember->member_type = $this->familyMemberModel->getMemberType($familyMember->id)->description;
    }

    $this->view('family/FamilyDetails', $data);
  }

  // Functie om de formuliergegevens op te halen en te sanitizen
  public function retrieveFormData($familyId = null)
  {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data = [
      'family_id' => $familyId,
      'last_name' => trim($_POST['last_name']),
      'street' => trim($_POST['street']),
      'house_number' => trim($_POST['house_number']),
      'postal_code' => trim($_POST['postal_code']),
      'city' => trim($_POST['city']),
      'country' => trim($_POST['country']),
      'last_name_err' => '',
      'street_err' => '',
      'house_number_err' => '',
      'postal_code_err' => '',
      'city_err' => '',
      'country_err' => '',
      'address_err' => ''
    ];

    return $data;
  }
}
