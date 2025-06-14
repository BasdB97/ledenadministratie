<?php

/**
 * BookyearController
 * 
 * Controller voor het beheren van boekjaren
 * 
 * Functies:
 * - Boekjaar toevoegen
 * - Boekjaar bewerken
 * - Boekjaar verwijderen
 * 
 */

class BookyearController extends Controller
{
  private $bookyearModel;

  public function __construct()
  {
    $this->bookyearModel = $this->model('Bookyear');
  }

  public function addBookyear()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'year' => trim($_POST['year']),
        'description' => empty(trim($_POST['description'])) ? null : trim($_POST['description']),
        'year_err' => '',
        'description_err' => '',
      ];

      // Validatie
      if (!is_numeric($data['year'])) {
        $data['year_err'] = 'Jaar moet een getal zijn.';
      } else if (strlen($data['year']) != 4) {
        $data['year_err'] = 'Jaar moet 4 cijfers lang zijn.';
      } else if ($this->bookyearModel->getBookyearByYear($data['year'])) {
        $data['year_err'] = 'Boekjaar bestaat al.';
      }

      // Als er geen fouten zijn, voeg het boekjaar toe
      if (empty($data['year_err'])) {
        if ($this->bookyearModel->addBookyear($data)) {
          flash('bookyear_message', 'Boekjaar succesvol toegevoegd.', 'alert-success');
          redirect('contributions/index');
        } else {
          flash('bookyear_message', 'Er ging iets mis bij het toevoegen van een nieuw boekjaar, probeer het opnieuw.', 'alert-danger');
          $this->view('bookyear/addBookyear', $data);
        }
      } else {
        $this->view('bookyear/addBookyear', $data);
      }
    }
    $this->view('bookyear/addBookyear');
  }

  public function editBookyear($year)
  {
    // Haal het boekjaar op
    $bookyear = $this->bookyearModel->getBookyearByYear((int)$year);
    // Als het boekjaar bestaat en het is het actieve boekjaar, geef een foutmelding
    if ($bookyear && $bookyear->year == $_SESSION['bookyear']) {
      flash('bookyear_message', 'Een actief boekjaar kan niet worden bewerkt.', 'alert-danger');
      redirect('contributions/index');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'bookyear' => $bookyear,
        'year' => trim($_POST['year']),
        'description' => empty(trim($_POST['description'])) ? null : trim($_POST['description']),
        'year_err' => '',
        'description_err' => '',
      ];
      
      // Als het jaar is gewijzigd, controleer of het jaar geldig is
      if ($data['year'] !== $bookyear->year) {
        if (!is_numeric($data['year'])) {
          $data['year_err'] = 'Jaar moet een getal zijn.';
        } else if (strlen($data['year']) != 4) {
          $data['year_err'] = 'Jaar moet 4 cijfers lang zijn.';
        } else if ($this->bookyearModel->getBookyearByYear($data['year'])) { // Controleer of het jaar bestaat
          $data['year_err'] = 'Boekjaar bestaat al.';
        }
      }

      // Als er geen fouten zijn, werk het boekjaar bij
      if (empty($data['year_err'])) {
        if ($this->bookyearModel->editBookyear($data)) {
          flash('bookyear_message', 'Boekjaar succesvol bijgewerkt.', 'alert-success');
          redirect('contributions/index');
        } else {
          flash('bookyear_message', 'Er ging iets mis bij het bijwerken van het boekjaar.', 'alert-danger');
          $this->view('bookyear/editBookyear', $data);
        }
      } else {
        $this->view('bookyear/editBookyear', $data);
      }
    }
    $data = [
      'bookyear' => $bookyear,
    ];
    $this->view('bookyear/editBookyear', $data);
  }

  public function deleteBookyear($bookyearId)
  {
    // Haal het boekjaar op
    $bookyear = $this->bookyearModel->getBookyearById((int)$bookyearId);

    // Als het boekjaar actief is, geef een foutmelding
    if ($bookyear && $bookyear->year == $_SESSION['bookyear']) {
      flash('bookyear_message', 'Het actieve boekjaar kan niet worden verwijderd.', 'alert-danger');
      redirect('contributions/index');
      return;
    }

    // Verwijder het boekjaar
    if ($this->bookyearModel->deleteBookyear((int)$bookyearId)) {
      flash('bookyear_message', 'Boekjaar succesvol verwijderd.', 'alert-success');
      redirect('contributions/index');
    } else {
      flash('bookyear_message', 'Er ging iets mis bij het verwijderen van het boekjaar.', 'alert-danger');
      redirect('contributions/index');
    }
  }
}
