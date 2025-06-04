<?php

/**
 * FamilyMember Klasse
 * 
 * Handelt alle familieleden in de database af
 * 
 * Functies:
 * - Contributie van een familielid ophalen
 * - Lidtype van een familielid ophalen
 * - Alle familieleden van een familie ophalen
 * - Alle familieleden van een boekjaar ophalen
 * - Familielid op id ophalen
 * - Familielid toevoegen
 * - Familielid bewerken
 * - Familielid verwijderen
 */
class FamilyMember
{

  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Haalt de volgende gegevens op:
   * 
   * - Alles van de tabel family_members
   * - Als er een contributie is, haal deze op, zet het anders op 0
   * - Boekjaar ID uit de tabel contributions
   */
  public function getMemberContribution($memberId, $bookyearId)
  {
    try {
      $this->db->query("SELECT fm.*, COALESCE(c.amount, 0) as amount, c.bookyear_id as bookyear_id
                      FROM family_members fm 
                      LEFT JOIN contributions c ON c.member_id = fm.id AND c.bookyear_id = :bookyearId 
                      WHERE fm.id = :memberId");
      $this->db->bind(':memberId', $memberId);
      $this->db->bind(':bookyearId', $bookyearId);
      return $this->db->single();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  // Haal het lidtype op van een bepaald familielid
  public function getMemberType($familyMemberId)
  {
    try {
      $this->db->query("SELECT mt.* FROM family_members fm INNER JOIN member_type mt ON fm.member_type_id = mt.id WHERE fm.id = :familyMemberId");
      $this->db->bind(':familyMemberId', $familyMemberId);
      return $this->db->single();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  // Haal alle familieleden op van een bepaalde familie
  public function getFamilyMembersByFamilyId($familyId)
  {
    try {
      $this->db->query("SELECT fm.*, f.last_name FROM family_members fm INNER JOIN family f ON fm.family_id = f.id WHERE fm.family_id = :familyId ORDER BY first_name ASC");
      $this->db->bind(':familyId', $familyId);
      return $this->db->resultSet();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  // Haal alle familieleden op van een boekjaar
  public function getAllFamilyMembers($bookyearId)
  {
    try {
      $this->db->query("SELECT f.last_name, c.amount as outstanding_contribution, fm.* FROM family_members fm INNER JOIN contributions c ON fm.id = c.member_id INNER JOIN family f ON fm.family_id = f.id WHERE c.bookyear_id = :bookyearId ORDER BY c.amount DESC");
      $this->db->bind(':bookyearId', $bookyearId);
      return $this->db->resultSet();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  // Haal een familielid + achternaam op op basis van ID
  public function getFamilyMemberById($familyMemberId)
  {
    try {
      $this->db->query("SELECT fm.*, f.last_name 
                      FROM family_members fm 
                      INNER JOIN family f ON fm.family_id = f.id 
                      WHERE fm.id = :familyMemberId");
      $this->db->bind(':familyMemberId', $familyMemberId);
      return $this->db->single();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function addFamilyMember($data)
  {
    try {
      $this->db->beginTransaction();

      // Voeg het familielid toe aan de database
      $this->db->query("INSERT INTO family_members (first_name, date_of_birth, age, member_type_id, family_id) VALUES (:firstName, :dateOfBirth, :age, :memberTypeId, :familyId)");
      $this->db->bind(':firstName', $data['first_name']);
      $this->db->bind(':dateOfBirth', $data['date_of_birth']);
      $this->db->bind(':age', $data['age']);
      $this->db->bind(':memberTypeId', $data['member_type_id']);
      $this->db->bind(':familyId', $data['family_id']);
      $this->db->execute();

      // Haal het toegevoegde lid-ID op
      $memberId = $this->db->lastInsertId();

      // Voeg de contributie toe
      $this->db->query("INSERT INTO contributions (member_id, amount, member_type, bookyear_id) VALUES (:memberId, :amount, :memberType, :bookyearId)");
      $this->db->bind(':memberId', $memberId);
      $this->db->bind(':amount', $data['contribution_amount']);
      $this->db->bind(':memberType', $data['member_type_id']);
      $this->db->bind(':bookyearId', $data['bookyear_id']);
      $this->db->execute();

      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function editFamilyMember($data)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("UPDATE family_members SET first_name = :firstName, date_of_birth = :dateOfBirth, age = :age, member_type_id = :memberTypeId, family_id = :familyId WHERE id = :familyMemberId");
      $this->db->bind(':firstName', $data['first_name']);
      $this->db->bind(':dateOfBirth', $data['date_of_birth']);
      $this->db->bind(':age', $data['age']);
      $this->db->bind(':memberTypeId', $data['member_type_id']);
      $this->db->bind(':familyId', $data['family_id']);
      $this->db->bind(':familyMemberId', $data['family_member_id']);
      $this->db->execute();
      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function deleteFamilyMember($familyMemberId)
  {
    try {
      $this->db->beginTransaction();

      // Verwijder de contributie
      $this->db->query("DELETE FROM contributions WHERE member_id = :familyMemberId");
      $this->db->bind(':familyMemberId', $familyMemberId);
      $this->db->execute();

      // Verwijder het familielid
      $this->db->query("DELETE FROM family_members WHERE id = :familyMemberId");
      $this->db->bind(':familyMemberId', $familyMemberId);
      $this->db->execute();

      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }
}
