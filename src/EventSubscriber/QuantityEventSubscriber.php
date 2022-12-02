<?php

namespace Drupal\farm_ledger\EventSubscriber;

use Drupal\quantity\Event\QuantityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Perform actions when quantity entities are saved/deleted.
 */
class QuantityEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      QuantityEvent::PRESAVE => 'quantityPresave',
    ];
  }

  /**
   * Autopopulate the quantity/unit price/total price on price quantities.
   *
   * @param \Drupal\quantity\Event\QuantityEvent $event
   *   Quantity event.
   */
  public function quantityPresave(QuantityEvent $event) {

    // If not a price quantity, bail.
    if ($event->quantity->bundle() !== 'price') {
      return;
    }

    // Get the quantity value, unit price, and total price fields.
    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $quantity_value_field */
    $quantity_value_field = $event->quantity->get('value');
    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $unit_price_field */
    $unit_price_field = $event->quantity->get('unit_price');
    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $total_price_field */
    $total_price_field = $event->quantity->get('total_price');

    // If we have a quantity value and unit price, but no total price, calculate
    // the total price.
    if (!$quantity_value_field->isEmpty() && !$unit_price_field->isEmpty() && $total_price_field->isEmpty()) {
      /** @var \Drupal\fraction\Fraction $quantity_value */
      $quantity_value = $quantity_value_field->fraction;
      /** @var \Drupal\fraction\Fraction $unit_price */
      $unit_price = $unit_price_field->fraction;
      $total_price = $unit_price->multiply($quantity_value);
      $event->quantity->set('total_price', ['numerator' => $total_price->getNumerator(), 'denominator' => $total_price->getDenominator()]);
    }

    // Or, if we have a quantity value and total price, but no unit price,
    // calculate the unit price.
    elseif (!$quantity_value_field->isEmpty() && !$total_price_field->isEmpty() && $unit_price_field->isEmpty()) {
      /** @var \Drupal\fraction\Fraction $quantity_value */
      $quantity_value = $quantity_value_field->fraction;
      /** @var \Drupal\fraction\Fraction $total_price */
      $total_price = $total_price_field->fraction;
      $unit_price = $total_price->divide($quantity_value);
      $event->quantity->set('unit_price', ['numerator' => $unit_price->getNumerator(), 'denominator' => $unit_price->getDenominator()]);
    }

    // Or, if we have a unit price and total price, but no quantity value,
    // calculate the quantity value.
    elseif (!$unit_price_field->isEmpty() && !$total_price_field->isEmpty() && $unit_price_field->isEmpty()) {
      /** @var \Drupal\fraction\Fraction $unit_price */
      $unit_price = $unit_price_field->fraction;
      /** @var \Drupal\fraction\Fraction $total_price */
      $total_price = $total_price_field->fraction;
      $quantity_value = $total_price->divide($unit_price);
      $event->quantity->set('value', ['numerator' => $quantity_value->getNumerator(), 'denominator' => $quantity_value->getDenominator()]);
    }
  }

}
