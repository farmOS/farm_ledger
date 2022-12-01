<?php

namespace Drupal\farm_ledger\Plugin\Quantity\QuantityType;

use Drupal\farm_entity\Plugin\Quantity\QuantityType\FarmQuantityType;

/**
 * Provides the price quantity type.
 *
 * @QuantityType(
 *   id = "price",
 *   label = @Translation("Price"),
 * )
 */
class Price extends FarmQuantityType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    // Inherit default quantity fields.
    $fields = parent::buildFieldDefinitions();

    // Add unit_quantity and unit_price fraction fields.
    $field_info = [
      'unit_price' => [
        'type' => 'fraction',
        'label' => $this->t('Unit price'),
        'weight' => [
          'form' => 6,
        ],
        'view_display_options' => [
          'label' => 'hidden',
          'type' => 'fraction_decimal',
          'settings' => [
            'precision' => 0,
            'auto_precision' => TRUE,
            'separator' => '/',
            'prefix_suffix' => FALSE,
          ],
        ],
      ],
      'total_price' => [
        'type' => 'fraction',
        'label' => $this->t('Total price'),
        'weight' => [
          'form' => 7,
        ],
        'view_display_options' => [
          'label' => 'hidden',
          'type' => 'fraction_decimal',
          'settings' => [
            'precision' => 0,
            'auto_precision' => TRUE,
            'separator' => '/',
            'prefix_suffix' => FALSE,
          ],
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
