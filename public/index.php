<?php

// Database configuratie laden
require_once '../app/config/config.php';

// Database library laden
require_once '../app/libraries/Database.php';

// Helper bestanden lader
require_once '../app/helpers/url_helper.php';
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/validation.php';

// Core classes autoloaden
spl_autoload_register(function ($className) {
  require_once '../app/libraries/' . $className . '.php';
});

$init = new Core();
