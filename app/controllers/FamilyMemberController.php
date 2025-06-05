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
        'familyMember' => $familyMember,
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

  public function retrieveFormData($familyId, $familyMemberId = null)
  {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data = [
      'family_id' => $familyId,
      'family_member_id' => $familyMemberId,
      'first_name' => trim($_POST['first_name']),
      'date_of_birth' => trim($_POST['date_of_birth']),
      'bookyear_id' => $this->bookyearId,
      'first_name_err' => '',
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

    // Bereken leeftijd en member_type
    if (empty($data['date_of_birth_err'])) {
      $currentDate = new DateTime();
      $age = $currentDate->diff($birthDate)->y;
      $data['age'] = $age;
      switch (true) {
        case ($age < 8):
          $data['member_type_id'] = 1;
          $data['discount_percentage'] = 55;
          break;
        case ($age >= 8 && $age <= 12):
          $data['member_type_id'] = 2;
          $data['discount_percentage'] = 40;
          break;
        case ($age >= 13 && $age <= 17):
          $data['member_type_id'] = 3;
          $data['discount_percentage'] = 25;
          break;
        case ($age >= 18 && $age <= 50):
          $data['member_type_id'] = 4;
          $data['discount_percentage'] = 0;
          break;
        case ($age >= 51):
          $data['member_type_id'] = 5;
          $data['discount_percentage'] = 45;
          break;
        default:
          $data['date_of_birth_err'] = 'Ongeldige leeftijd.';
      }

      // Bereken contributie
      if (empty($data['date_of_birth_err'])) {
        $data['contribution_amount'] = round(100 * (1 - $data['discount_percentage'] / 100));
      }
    }

    // Check of er geen fouten zijn
    return empty($data['first_name_err']) && empty($data['date_of_birth_err']);
  }
}
