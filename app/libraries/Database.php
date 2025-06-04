<?php

/**
 * Database
 * 
 * Verwerkt de database connectie en query's
 * 
 * Functies:
 * - Database connectie maken
 * - Query uitvoeren
 * - Binden van waardes aan parameters
 * - Query uitvoeren
 * - Resultaat op verschillende manieren ophalen
 * - Transacties starten, commiten en rollbacken
 * 
 */

class Database
{
  // Database gegevens
  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbname = DB_NAME;

  // Database connectie
  private $dbh;
  private $stmt;
  private $error;

  // Database connectie maken
  public function __construct()
  {
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
      $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      echo $this->error;
    }
  }

  // Voer een query uit
  public function query($sql)
  {
    $this->stmt = $this->dbh->prepare($sql);
  }

  // Bind de waarde aan de parameter
  public function bind($param, $value, $type = null)
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }
    $this->stmt->bindValue($param, $value, $type);
  }

  // Uitvoeren van een query
  public function execute()
  {
    return $this->stmt->execute();
  }

  // Resultaat als een array van objecten
  public function resultSet()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  // Resultaat als een array van associatieve arrays
  public function resultSetAssoc()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Krijg 1 rij als object
  public function single()
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  // Krijg de waarde van één kolom
  public function fetchColumn($columnNumber = 0)
  {
    $this->execute();
    return $this->stmt->fetchColumn($columnNumber);
  }

  // Krijg het aantal rijen
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }

  // Begin een transactie
  public function beginTransaction()
  {
    return $this->dbh->beginTransaction();
  }

  // Commit een transactie
  public function commit()
  {
    return $this->dbh->commit();
  }

  // Rollback een transactie en opslaan van error bericht
  public function rollBack($message)
  {
    error_log('Rollback transactie, error: ' . $message);
    return $this->dbh->rollBack();
  }

  // Krijg de ID van de laatste ingevoegde rij
  public function lastInsertId()
  {
    return $this->dbh->lastInsertId();
  }
}
