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
   * Auto-populate the value field on price quantities.
   *
   * @param \Drupal\quantity\Event\QuantityEvent $event
   *   Quantity event.
   */
  public function quantityPresave(QuantityEvent $event) {

    // If not a price quantity, bail.
    if ($event->quantity->bundle() !== 'price') {
      return;
    }

    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $unit_price_field */
    $unit_price_field = $event->quantity->get('unit_price');
    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $quantity_value_field */
    $quantity_value_field = $event->quantity->get('value');
    if (!$unit_price_field->isEmpty() && !$quantity_value_field->isEmpty()) {

      // Calculate the total price.
      /** @var \Drupal\fraction\Fraction $unit_price */
      $unit_price = $unit_price_field->fraction;
      /** @var \Drupal\fraction\Fraction $unit_quantity */
      $unit_quantity = $quantity_value_field->fraction;
      $total_price = $unit_price->multiply($unit_quantity);

      // Save the computed total price.
      $event->quantity->set('total_price', ['numerator' => $total_price->getNumerator(), 'denominator' => $total_price->getDenominator()]);
    }
  }

}
