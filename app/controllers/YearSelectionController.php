<?php

/**
 * Controller voor het selecteren van een boekjaar
 * 
 * Haalt alle boekjaren op en geeft deze mee aan de view
 * Na het selecteren van een boekjaar, wordt deze opgeslagen in de session
 */
class YearSelectionController extends Controller
{
  private $bookyearModel;

  public function __construct()
  {
    $this->bookyearModel = $this->model('Bookyear');
  }

  public function index()
  {
    $bookyears = $this->bookyearModel->getAllBookyears();
    $data = [
      'bookyears' => $bookyears,
      'currentYear' => date('Y'),
    ];

    $this->view('yearselection/index', $data);
  }


  public function selectYear()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $selectedYear = filter_var($_POST['year'], FILTER_VALIDATE_INT);
      $bookyear = $this->bookyearModel->getBookyearByYear($selectedYear);
      setSession('bookyearId', $bookyear->id);
      setSession('bookyear', $bookyear->year);
      redirect('dashboard/index');
    } else {
      redirect('yearselection/index');
    }
  }
}
