<?php

/**
 * Family Klasse
 * 
 * Handelt alle familiegegevens in de database af
 * 
 * Functies:
 * - Alle families ophalen
 * - Familie op id ophalen
 * - Controle of een adres al bestaat in de database
 * - Familie toevoegen
 * - Familie bewerken
 * - Familie verwijderen
 * 
 */
class Family
{
  private $db;
  public function __construct()
  {
    $this->db = new Database();
  }

  // Haal alle families op met het aantal leden
  public function getAllFamilies()
  {
    try {
      $this->db->query("SELECT f.*, COUNT(fm.id) AS member_count FROM family f LEFT JOIN family_members fm ON f.id = fm.family_id GROUP BY f.id");
      return $this->db->resultSet();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function getFamilyById($family_id)
  {
    try {
      $this->db->query("SELECT * FROM family WHERE id = :id");
      $this->db->bind(':id', $family_id);
      return $this->db->single();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  // Controleer of een adres al bestaat in de database
  public function checkAddressExists($data)
  {
    try {
      // Telt het aantal met het opgegeven adres waar het ID niet overeenkomt met het opgegeven ID.
      $this->db->query("SELECT COUNT(*) as count
                         FROM family 
                         WHERE street = :street 
                           AND house_number = :house_number 
                           AND postal_code = :postal_code 
                           AND city = :city 
                           AND country = :country 
                           AND id != :family_id");
      $this->db->bind(':street', $data['street']);
      $this->db->bind(':house_number', $data['house_number']);
      $this->db->bind(':postal_code', $data['postal_code']);
      $this->db->bind(':city', $data['city']);
      $this->db->bind(':country', $data['country']);
      $this->db->bind(':family_id', isset($data['family_id']) ? $data['family_id'] : -1, PDO::PARAM_INT);
      $result = $this->db->single();
      return $result->count > 0;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function addFamily($data)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("INSERT INTO family (last_name, street, house_number, postal_code, city, country) VALUES (:name, :street, :house_number, :postal_code, :city, :country)");
      $this->db->bind(':name', $data['last_name']);
      $this->db->bind(':street', $data['street']);
      $this->db->bind(':house_number', $data['house_number']);
      $this->db->bind(':postal_code', $data['postal_code']);
      $this->db->bind(':city', $data['city']);
      $this->db->bind(':country', $data['country']);
      $result = $this->db->execute();
      $this->db->commit();
      return $result;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function updateFamily($data)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("UPDATE family SET last_name = :last_name, street = :street, house_number = :house_number, postal_code = :postal_code, city = :city, country = :country WHERE id = :id");
      $this->db->bind(':id', $data['family_id']);
      $this->db->bind(':last_name', $data['last_name']);
      $this->db->bind(':street', $data['street']);
      $this->db->bind(':house_number', $data['house_number']);
      $this->db->bind(':postal_code', $data['postal_code']);
      $this->db->bind(':city', $data['city']);
      $this->db->bind(':country', $data['country']);
      $result = $this->db->execute();
      $this->db->commit();
      return $result;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function deleteFamily($familyId)
  {
    try {
      $this->db->beginTransaction();

      // Haal eerst alle familieleden op
      $this->db->query("SELECT id FROM family_members WHERE family_id = :familyId");
      $this->db->bind(':familyId', $familyId);
      $familyMembers = $this->db->resultSet();

      // Verwijder alle contributies van de familieleden
      foreach ($familyMembers as $member) {
        $this->db->query("DELETE FROM contributions WHERE member_id = :memberId");
        $this->db->bind(':memberId', $member->id);
        $this->db->execute();
      }

      // Verwijder alle familieleden van de familie
      $this->db->query("DELETE FROM family_members WHERE family_id = :familyId");
      $this->db->bind(':familyId', $familyId);
      if (!$this->db->execute()) {
        throw new PDOException("Fout bij verwijderen van familieleden");
      }

      // Verwijder de familie
      $this->db->query("DELETE FROM family WHERE id = :familyId");
      $this->db->bind(':familyId', $familyId);
      if (!$this->db->execute()) {
        throw new PDOException("Fout bij verwijderen van de familie");
      }

      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }
}
