<?php

/**
 * @file
 * Provides Views data for farm_ledger.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_ledger_views_data_alter(array &$data) {

  // Create unit price field, filter, and sort using fraction decimal handlers.
  $fraction_fields = [
    'numerator' => 'unit_price_numerator',
    'denominator' => 'unit_price_denominator',
  ];
  $data['quantity__unit_price']['unit_price_value'] = [
    'title' => t('Unit price'),
    'help' => t('Value of the unit price, in decimal format.'),
    'real field' => 'unit_price_numerator',
    'field' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
      'click sortable' => TRUE,
    ],
    'sort' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
    ],
    'filter' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
    ],
  ];

  // Create total price field, filter, and sort using fraction decimal
  // handlers.
  $fraction_fields = [
    'numerator' => 'total_price_numerator',
    'denominator' => 'total_price_denominator',
  ];
  $data['quantity__total_price']['total_price_value'] = [
    'title' => t('Total price'),
    'help' => t('Value of the total price, in decimal format.'),
    'real field' => 'total_price_numerator',
    'field' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
      'click sortable' => TRUE,
    ],
    'sort' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
    ],
    'filter' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
    ],
  ];
}
