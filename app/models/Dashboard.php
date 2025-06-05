<?php

/**
 * Dashboard model
 * 
 * Haalt de dashboard informatie op
 * 
 */

class Dashboard
{

  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /*
    * Haalt de volgende gegevens op:
    * - Alles uit de tabel family
    * - De voornaam, geboortedatum en aantal leden van een familie uit de family_members tabel
    * - Het totaalbedrag van de contributies in het gekozen boekjaar van een familie uit de contributions tabel
  */
  public function getDashboardInformation($bookyearId)
  {
    try {
      $this->db->query("
                SELECT f.*,
                  fm.first_name,
                  fm.date_of_birth,
                  (SELECT COUNT(*) FROM family_members fm2 WHERE fm2.family_id = fm.family_id) AS member_count,
                  (SELECT SUM(c.amount)
                    FROM contributions c
                    INNER JOIN family_members fm3 on c.member_id = fm3.id
                    WHERE fm3.family_id = fm.family_id
                    AND c.bookyear_id = :bookyearId) AS total_contribution
                FROM family f
                INNER JOIN family_members fm ON fm.family_id = f.id
                INNER JOIN contributions c ON fm.id = c.member_id
                WHERE c.bookyear_id = :bookyearId 
      ");
      $this->db->bind(':bookyearId', $bookyearId);
      return $this->db->resultSet();
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }
}
