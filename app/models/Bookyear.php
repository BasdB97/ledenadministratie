<?php

/**
 * Bookyear model
 * 
 * Ophalen en beheren van boekjaren
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
      $this->db->commit();
      return $result;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
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
