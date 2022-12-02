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
        'description' => $this->t('The price per unit. If left blank, this will be automatically calculated.'),
        'weight' => [
          'form' => 11,
          'view' => 11,
        ],
      ],
      'total_price' => [
        'type' => 'fraction',
        'label' => $this->t('Total price'),
        'description' => $this->t('The total price. If left blank, this will be automatically calculated.'),
        'weight' => [
          'form' => 12,
          'view' => 12,
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
