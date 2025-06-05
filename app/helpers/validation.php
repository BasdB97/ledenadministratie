<?php

/**
 * validation.php
 * 
 * Helper functies voor formulieren
 * 
 * Functies:
 * - Validatie functie voor formulieren
 * - Controleert of er errors zijn
 * 
 */

// Validatie functie voor formulieren
function validateForm($data)
{
  // Familienaam validatie, mag niet leeg zijn en mag geen cijfers bevatten
  if (empty($data['last_name'])) {
    $data['name_err'] = 'Vul een naam in.';
  } elseif (preg_match('/[0-9]/', $data['last_name'])) {
    $data['name_err'] = 'De familienaam mag geen cijfers bevatten.';
  }

  // Straatnaam validatie, mag niet leeg zijn en mag geen cijfers bevatten
  if (empty($data['street'])) {
    $data['street_err'] = 'Vul een straatnaam in.';
  } elseif (preg_match('/[0-9]/', $data['street'])) {
    $data['street_err'] = 'De straatnaam mag geen cijfers bevatten.';
  }

  // Huisnummer validatie, mag niet leeg zijn en moet uit cijfers bestaan, eventueel gevolgd door één letter
  if (empty($data['house_number'])) {
    $data['house_number_err'] = 'Vul een huisnummer in.';
  } elseif (!preg_match('/^[0-9]+[a-zA-Z]?$/', $data['house_number'])) {
    $data['house_number_err'] = 'Het huisnummer moet uit cijfers bestaan, eventueel gevolgd door één letter.';
  }

  // Postcode validatie, mag niet leeg zijn en moet uit 4 cijfers en 2 letters bestaan (bijv. 1234AB)
  if (empty($data['postal_code'])) {
    $data['postal_code_err'] = 'Vul een postcode in.';
  } elseif (!preg_match('/^[0-9]{4}[A-Z]{2}$/', $data['postal_code'])) {
    $data['postal_code_err'] = 'Vul een geldige postcode in (bijv. 1234AB).';
  }

  // Plaatsnaam validatie, mag niet leeg zijn en mag geen cijfers bevatten
  if (empty($data['city'])) {
    $data['city_err'] = 'Vul een plaats in.';
  } elseif (preg_match('/[0-9]/', $data['city'])) {
    $data['city_err'] = 'De plaatsnaam mag geen cijfers bevatten.';
  }

  // Land validatie, mag niet leeg zijn
  if (empty($data['country'])) {
    $data['country_err'] = 'Vul het land in.';
  }

  return $data;
}

// Controleert of er errors zijn
function checkErrors($data)
{
  if (
    !empty($data['name_err']) || !empty($data['street_err']) || !empty($data['house_number_err']) ||
    !empty($data['postal_code_err']) || !empty($data['city_err']) || !empty($data['country_err']) ||
    !empty($data['address_err'])
  ) {
    return true;
  }
  return false;
}
