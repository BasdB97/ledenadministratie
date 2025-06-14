<?php

/**
 * Bookyear model
 * 
 * Ophalen en beheren van boekjaren
 * 
 * Functies:
 * - Alle boekjaren ophalen
 * - Boekjaar op jaar ophalen
 * - Boekjaar op id ophalen
 * - Totaalbedrag van contributies ophalen
 * - Boekjaar toevoegen
 * - Boekjaar bewerken
 * - Boekjaar verwijderen
 */

class Bookyear
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllBookyears()
  {
    try {
      $this->db->query("SELECT * FROM bookyear ORDER BY year DESC");
      return $this->db->resultSet();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function getBookyearByYear($year)
  {
    try {
      $this->db->query("SELECT * FROM bookyear WHERE year = :year");
      $this->db->bind(':year', $year);
      return $this->db->single();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function getBookyearById($bookyearId)
  {
    try {
      $this->db->query("SELECT * FROM bookyear WHERE id = :bookyearId");
      $this->db->bind(':bookyearId', $bookyearId);
      return $this->db->single();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function getTotalContribution($bookyearId)
  {
    try {
      $this->db->query("SELECT SUM(amount) as total_contribution FROM contributions WHERE bookyear_id = :bookyearId");
      $this->db->bind(':bookyearId', $bookyearId);
      return $this->db->single()->total_contribution;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }


  public function addBookyear($data)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("INSERT INTO bookyear (year, description) VALUES (:year, :description)");
      $this->db->bind(':year', $data['year']);
      $this->db->bind(':description', $data['description']);
      $result = $this->db->execute();

      // Haal het ID van het nieuwe boekjaar op
      $newBookyearId = $this->db->lastInsertId();

      // Maak contributies aan voor alle bestaande leden
      $this->createNewBookyear($newBookyearId);

      $this->db->commit();
      return $result;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  // Nieuw boekjaar aanmaken en contributies toevoegen
  private function createNewBookyear($bookyearId)
  {
    try {
      // Haal alle familieleden op
      $this->db->query("SELECT * FROM family_members");
      $members = $this->db->resultSet();

      // Voor elk lid, bereken de contributie en voeg deze toe
      foreach ($members as $member) {
        // Bereken leeftijd en kortingspercentage
        $memberData = calculateAgeAndDiscount($member->date_of_birth);

        // Bereken contributiebedrag
        $contributionAmount = round(100 * (1 - $memberData['discount_percentage'] / 100));

        // Voeg contributie toe
        $this->db->query("INSERT INTO contributions (member_id, amount, member_type, bookyear_id) VALUES (:memberId, :amount, :memberType, :bookyearId)");
        $this->db->bind(':memberId', $member->id);
        $this->db->bind(':amount', $contributionAmount);
        $this->db->bind(':memberType', $memberData['member_type_id']);
        $this->db->bind(':bookyearId', $bookyearId);
        $this->db->execute();
      }

      return true;
    } catch (PDOException $e) {
      throw $e;
    }
  }

  public function editBookyear($data)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("UPDATE bookyear SET year = :newYear, description = :description WHERE year = :oldYear");
      $this->db->bind(':oldYear', $data['bookyear']->year);
      $this->db->bind(':newYear', $data['year']);
      $this->db->bind(':description', $data['description']);
      $this->db->execute();
      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  public function deleteBookyear($bookyearId)
  {
    try {
      $this->db->beginTransaction();

      // Controleert of er contributies zijn voor het boekjaar
      $this->db->query("SELECT COUNT(*) as count FROM contributions WHERE bookyear_id = :bookyearId");
      $this->db->bind(':bookyearId', $bookyearId);
      $result = $this->db->single();

      // Verwijder contributies als ze bestaan in het boekjaar
      if ($result->count > 0) {
        $this->db->query("DELETE FROM contributions WHERE bookyear_id = :bookyearId");
        $this->db->bind(':bookyearId', $bookyearId);
        $this->db->execute();
      }

      // Verwijder het boekjaar
      $this->db->query("DELETE FROM bookyear WHERE id = :bookyearId");
      $this->db->bind(':bookyearId', $bookyearId);
      $this->db->execute();

      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }
}
