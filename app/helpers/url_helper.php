<?php

/**
 * url_helper.php
 * 
 * Helper functies voor URL's
 * 
 * Stuurt de gebruiker naar de opgegeven URL
 * 
 */

function redirect($page)
{
  header('location: ' . URL_ROOT . '/' . $page);
}
