<?php

/**
 * Users model
 * 
 * Handelt controleren van gebruikers en login af
 * 
 * Functies:
 * - Controle of een gebruiker bestaat
 * - Login functie
 */

class Users
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  // Controleer of de gebruiker bestaat
  public function findUserByUsername($username)
  {
    try {
      $this->db->query('SELECT * FROM users WHERE username = :username');
      $this->db->bind(':username', $username);
      $row = $this->db->single();
      return $this->db->rowCount() > 0;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }

  // Log de gebruiker in met de gegeven gebruikersnaam en wachtwoord
  public function login($username, $password)
  {
    try {
      $this->db->query('SELECT * FROM users WHERE username = :username');
      $this->db->bind(':username', $username);
      $row = $this->db->single();
      return password_verify($password, $row->password) ? $row : false;
    } catch (PDOException $e) {
      $this->db->rollBack($e->getMessage());
      return false;
    }
  }
}
