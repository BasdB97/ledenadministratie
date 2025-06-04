<?php

/**
 * AuthController
 * 
 * Controller voor het inloggen en uitloggen van gebruikers
 * 
 * Functies:
 * - Inloggen
 * - Uitloggen
 * 
 */

class AuthController extends Controller
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = $this->model('Users');
  }

  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data = [
        'username' => strip_tags(trim($_POST['username'])),
        'password' => trim($_POST['password']),
        'error' => '',
      ];

      // Validatie 
      if (empty($data['username'])) {
        $data['error'] = 'Vul een gebruikersnaam in';
      }
      if (empty($data['password'])) {
        $data['error'] = 'Vul een wachtwoord in';
      }

      // Als er geen fouten zijn, controleer of de gebruiker bestaat
      if (empty($data['error'])) {
        if ($this->userModel->findUserByUsername($data['username'])) {
          // Als de gebruiker bestaat, log de gebruiker in
          $loggedInUser = $this->userModel->login($data['username'], $data['password']);
          if ($loggedInUser) {
            // Als de gebruiker is ingelogd, stel de sessie in
            setSession('username', $loggedInUser->username);
            setSession('userRole', $loggedInUser->role);
            redirect('yearselection/index');
          } else {
            $data['error'] = 'Wachtwoord is incorrect';
            return $this->view('auth/login', $data);
          }
        } else {
          $data['error'] = 'Gebruiker niet gevonden';
          $this->view('auth/login', $data);
        }
      }
    }
    $this->view('auth/login');
  }

  // Bij uitloggen wordt de sessie verwijderd
  public function logout()
  {
    session_unset();
    session_destroy();
    redirect('auth/login');
  }
}
