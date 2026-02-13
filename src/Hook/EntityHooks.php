<?php

declare(strict_types=1);

namespace Drupal\farm_ledger\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Entity hook implementations for farm_ledger.
 */
class EntityHooks {

  /**
   * Implements hook_entity_type_alter().
   */
  #[Hook('entity_type_alter')]
  public function entityTypeAlter(array &$entity_types) {
    $entity_types['quantity']->addConstraint('TotalPrice');
  }

}
