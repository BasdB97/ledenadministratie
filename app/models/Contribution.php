<?php

/**
 * Contribution model
 * 
 * Handelt contributies in de database af
 * 
 * Functies:
 * - Controle of contributie bestaat
 * - Contributie toevoegen
 * - Contributie bewerken
 * - Contributie verwijderen
 * 
 */
class Contribution
{

  private $db;
  public function __construct()
  {
    $this->db = new Database();
  }

  // Controleert of een contributie bestaat
  public function contributionExists($familyMemberId, $bookyearId)
  {
    try {
      $this->db->query("SELECT COUNT(*) as count FROM contributions WHERE member_id = :familyMemberId AND bookyear_id = :bookyearId");
      $this->db->bind(':familyMemberId', $familyMemberId);
      $this->db->bind(':bookyearId', $bookyearId);
      return $this->db->single()->count > 0;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function addContribution($data)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("INSERT INTO contributions (member_id, amount, member_type, bookyear_id) VALUES (:member_id, :amount, :member_type_id, :bookyear_id)");
      $this->db->bind(':member_id', $data['familyMember']->id);
      $this->db->bind(':amount', $data['newContributionAmount']);
      $this->db->bind(':member_type_id', $data['familyMember']->member_type_id);
      $this->db->bind(':bookyear_id', $data['bookyearId']);
      $result = $this->db->execute();
      $this->db->commit();
      return $result;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function updateMemberContribution($familyMemberId, $bookyearId, $newAmount)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("UPDATE contributions SET amount = :amount WHERE member_id = :familyMemberId AND bookyear_id = :bookyearId");
      $this->db->bind(':amount', $newAmount);
      $this->db->bind(':familyMemberId', $familyMemberId);
      $this->db->bind(':bookyearId', $bookyearId);
      $result = $this->db->execute();
      $this->db->commit();
      return $result;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }


  public function deleteContribution($contributionId)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("DELETE FROM contributions WHERE id = :contributionId");
      $this->db->bind(':contributionId', $contributionId);
      $result = $this->db->execute();
      $this->db->commit();
      return $result;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }
}
