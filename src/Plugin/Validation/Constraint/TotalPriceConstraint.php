<?php

namespace Drupal\farm_ledger\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that the total price equals unit price * quantity.
 *
 * @Constraint(
 *   id = "TotalPrice",
 *   label = @Translation("Total price", context = "Validation"),
 * )
 */
class TotalPriceConstraint extends Constraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'The total price must be equal to the unit price multiplied by quantity value.';

}
