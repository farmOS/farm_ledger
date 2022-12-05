<?php

namespace Drupal\farm_ledger\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the TotalPrice constraint.
 */
class TotalPriceConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($quantity, Constraint $constraint) {
    /** @var \Drupal\quantity\Entity\QuantityInterface $quantity */
    /** @var \Drupal\farm_ledger\Plugin\Validation\Constraint\TotalPriceConstraint $constraint */

    // Only continue if this is a price quantity.
    if ($quantity->bundle() != 'price') {
      return;
    }

    // Get the quantity value, unit price, and total price fields.
    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $quantity_value_field */
    $quantity_value_field = $quantity->get('value');
    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $unit_price_field */
    $unit_price_field = $quantity->get('unit_price');
    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $total_price_field */
    $total_price_field = $quantity->get('total_price');

    // If any of the fields are empty, bail.
    if ($quantity_value_field->isEmpty() || $unit_price_field->isEmpty() || $total_price_field->isEmpty()) {
      return;
    }

    // If the total price does not equal the quantity value multiplied by the
    // unit price, add a violation.
    $quantity_value = $quantity_value_field->decimal;
    $unit_price = $unit_price_field->decimal;
    $total_price = $total_price_field->decimal;
    if ($quantity_value * $unit_price != $total_price) {
      $this->context->buildViolation($constraint->message)
        ->atPath('total_price')
        ->addViolation();
    }
  }

}
