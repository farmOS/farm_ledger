<?php

declare(strict_types=1);

namespace Drupal\farm_ledger\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Views hook implementations for farm_ledger.
 */
class ViewsHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_views_data_alter().
   */
  #[Hook('views_data_alter')]
  public function viewsDataAlter(array &$data) {

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
      'title' => $this->t('Total price'),
      'help' => $this->t('Value of the total price, in decimal format.'),
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

}
