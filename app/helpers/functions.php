<?php

/**
 * 
 * Helper functies 
 * 
 * Functies:
 * - Validatie functie voor formulieren
 * - Controleert of er errors zijn
 * - Berekent leeftijd en type lid
 * - Berekent kortingspercentage
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
  } elseif (preg_match('/[0-9]/', $data['city']) || preg_match('/[^a-zA-Z\s]/', $data['city'])) {
    $data['city_err'] = 'De plaatsnaam mag geen cijfers of speciale tekens bevatten.';
  }

  // Land validatie, mag niet leeg zijn
  if (empty($data['country'])) {
    $data['country_err'] = 'Vul het land in.';
  }

  return $data;
}

// Controleer of er errors zijn
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


// Bereken leeftijd, type lid en kortingspercentage
function calculateAgeAndDiscount($dateOfBirth)
{
  $birthDate = new DateTime($dateOfBirth);
  $currentDate = new DateTime();
  $age = $currentDate->diff($birthDate)->y;

  $data = [
    'age' => $age,
    'member_type_id' => 4, // Default: Senior, €100
    'discount_percentage' => 0
  ];

  switch (true) {
    case ($age < 8):
      $data['member_type_id'] = 1;
      $data['discount_percentage'] = 55;
      break;
    case ($age >= 8 && $age <= 12):
      $data['member_type_id'] = 2;
      $data['discount_percentage'] = 40;
      break;
    case ($age >= 13 && $age <= 17):
      $data['member_type_id'] = 3;
      $data['discount_percentage'] = 25;
      break;
    case ($age >= 18 && $age <= 50):
      $data['member_type_id'] = 4;
      $data['discount_percentage'] = 0;
      break;
    case ($age >= 51):
      $data['member_type_id'] = 5;
      $data['discount_percentage'] = 45;
      break;
  }

  return $data;
}
