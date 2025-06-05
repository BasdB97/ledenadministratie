<?php

/**
 * Core class
 * 
 * Handelt de URL opname en routing af, stelt de controller en method in en geeft de parameters mee
 * 
 */
class Core
{
  // Controller, method en parameters met standard waardes
  protected $currentController = 'Auth';
  protected $currentMethod = 'login';
  protected $params = [];

  public function __construct()
  {
    $url = $this->getUrl();

    // Als er een controller in de array staat, en dit bestand bestaat, sla deze op in de currentController variabele
    if (isset($url[0])) {
      $controllerFile = '../app/controllers/' . ucwords($url[0]) . 'Controller.php';
      if (file_exists($controllerFile)) {
        $this->currentController = ucwords($url[0]);
        unset($url[0]);
      }
    }

    // Maak de controller path aan, require deze en maak een nieuwe instantie van de controller
    $controllerPath = '../app/controllers/' . $this->currentController . 'Controller.php';
    require_once $controllerPath;
    $this->currentController = $this->currentController . 'Controller';
    $this->currentController = new $this->currentController;

    // Als er een method in de array staat, en deze bestaat in de controller, sla deze op in de currentMethod variabele
    if (isset($url[1])) {
      if (method_exists($this->currentController, $url[1])) {
        $this->currentMethod = $url[1];
        unset($url[1]);
      } else {
        $this->currentMethod = 'index';
      }
    }

    // Als er parameters in de array staan, sla deze op in de params variabele
    $this->params = $url ? array_values($url) : [];

    // Roep de current method van de current controller aan met de params
    call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
  }

  // Haalt de URL op, maakt deze schoon en splitst deze in een array
  public function getUrl()
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    }
  }
}
