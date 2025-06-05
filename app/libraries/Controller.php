<?php

/**
 * Basis controller klasse
 * 
 * Laad de models en views
 * 
 */
class Controller
{
  public function model($model)
  {
    require_once '../app/models/' . $model . '.php';

    return new $model();
  }

  public function view($view, $data = [])
  {
    // Check of de view bestaat
    if (file_exists('../app/views/' . $view . '.php')) {
      require_once '../app/views/' . $view . '.php';
    } else {
      // View bestaat niet
      die('View bestaat niet');
    }
  }
}
