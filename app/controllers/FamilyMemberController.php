<?php

/**
 * FamilyMemberController
 * 
 * Controller voor het beheren van familieleden
 * 
 * Functies:
 * - Familieleden toevoegen
 * - Familieleden bewerken
 * - Familieleden verwijderen
 * - Contributie en type lid bepalen
 * 
 */

class FamilyMemberController extends Controller
{
  private $familyMemberModel;
  private $familyModel;
  private $bookyearModel;
  private $bookyearId;

  public function __construct()
  {
    $this->familyMemberModel = $this->model('FamilyMember');
    $this->familyModel = $this->model('Family');
    $this->bookyearModel = $this->model('Bookyear');
    $this->bookyearId = $this->bookyearModel->getBookyearByYear((int)$_SESSION['bookyear'])->id;
  }

  public function addFamilyMember($familyId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $this->retrieveFormData($familyId);

      if ($this->processFamilyMemberData($data)) {
        if ($this->familyMemberModel->addFamilyMember($data)) {
          flash('family_member_message', 'Familielid succesvol toegevoegd.', 'alert-success');
          redirect('family/listFamilyDetails/' . $familyId);
        } else {
          flash('family_member_message', 'Er ging iets mis bij het toevoegen van het familielid, probeer het opnieuw.', 'alert-danger');
          $this->view('familyMember/addFamilyMember', $data);
        }
      } else {
        $this->view('familyMember/addFamilyMember', $data);
      }
    } else {

      $family = $this->familyModel->getFamilyById($familyId);
      $data = [
        'family_id' => $familyId,
        'last_name' => $family->last_name,
      ];

      $this->view('familyMember/addFamilyMember', $data);
    }
  }

  public function editFamilyMember($familyMemberId)
  {
    $familyMember = $this->familyMemberModel->getFamilyMemberById($familyMemberId);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $this->retrieveFormData($familyMember->family_id, $familyMemberId);

      if ($this->processFamilyMemberData($data)) {
        if ($this->familyMemberModel->editFamilyMember($data)) {
          flash('family_member_message', 'Familielid succesvol bijgewerkt.', 'alert-success');
          redirect('family/listFamilyDetails/' . $data['family_id']);
        } else {
          flash('family_member_message', 'Er ging iets mis bij het bijwerken van het familielid, probeer het opnieuw.', 'alert-danger');
          $this->view('familyMember/editFamilyMember', $data);
        }
      } else {
        $this->view('familyMember/editFamilyMember', $data);
      }
    } else {

      $data = [
        'family_member_type' => $familyMember->family_member_type,
        'family_member_id' => $familyMemberId,
        'family_id' => $familyMember->family_id,
        'first_name' => $familyMember->first_name,
        'last_name' => $familyMember->last_name,
        'date_of_birth' => $familyMember->date_of_birth,
      ];
      $this->view('familyMember/editFamilyMember', $data);
    }
  }

  public function deleteFamilyMember($familyMemberId, $familyId)
  {
    if ($this->familyMemberModel->deleteFamilyMember($familyMemberId)) {
      flash('family_member_message', 'Familielid succesvol verwijderd.', 'alert-success');
      redirect('family/listFamilyDetails/' . $familyId);
    }
  }

  // Haal de gegevens op uit het formulier en sanitize de data
  public function retrieveFormData($familyId, $familyMemberId = null)
  {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data = [
      'family_id' => $familyId,
      'family_member_id' => $familyMemberId,
      'first_name' => trim($_POST['first_name']),
      'family_member_type' => trim($_POST['family_member_type']),
      'date_of_birth' => trim($_POST['date_of_birth']),
      'bookyear_id' => $this->bookyearId,
      'first_name_err' => '',
      'family_member_type_err' => '',
      'date_of_birth_err' => '',
    ];
    return $data;
  }

  private function processFamilyMemberData(array &$data)
  {
    // Valideer de voornaam
    if (empty($data['first_name'])) {
      $data['first_name_err'] = 'Vul een naam in.';
    } elseif (preg_match('/[0-9]/', $data['first_name'])) {
      $data['first_name_err'] = 'De voornaam mag geen cijfers bevatten.';
    }

    // Valideer de geboortedatum
    if (empty($data['date_of_birth'])) {
      $data['date_of_birth_err'] = 'Vul een geboortedatum in.';
    } else {
      $birthDate = DateTime::createFromFormat('Y-m-d', $data['date_of_birth']);
      if (!$birthDate || $birthDate->format('Y-m-d') !== $data['date_of_birth']) {
        $data['date_of_birth_err'] = 'Vul een geldige geboortedatum in (bijv. 1980-10-10).';
      } elseif ($birthDate > new DateTime()) {
        $data['date_of_birth_err'] = 'Geboortedatum kan niet in de toekomst liggen.';
      } elseif ((new DateTime())->diff($birthDate)->y > 120) {
        $data['date_of_birth_err'] = 'Leeftijd mag niet hoger zijn dan 120 jaar.';
      }
    }

    // Bereken leeftijd en type lid
    if (empty($data['date_of_birth_err'])) {
      $memberData = calculateAgeAndDiscount($data['date_of_birth']);
      $data['age'] = $memberData['age'];
      $data['member_type_id'] = $memberData['member_type_id'];
      $data['discount_percentage'] = $memberData['discount_percentage'];

      // Bereken contributie
      $data['contribution_amount'] = round(100 * (1 - $data['discount_percentage'] / 100));
    }

    // Check of er geen fouten zijn
    return empty($data['first_name_err']) && empty($data['date_of_birth_err']);
  }
}
