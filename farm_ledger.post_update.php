<?php

/**
 * @file
 * Post update hooks for the farm_ledger module.
 */

/**
 * Update price quantity data structure and migrate data.
 */
function farm_ledger_post_update_migrate_price_quantity_total_price(&$sandbox) {

  // This function will be run as a batch operation. On the first run, we will
  // make preparations. This logic should only run once.
  if (!isset($sandbox['current_quantity'])) {

    // Query the database for all price quantity IDs.
    $sandbox['price_quantity_ids'] = \Drupal::database()->query("SELECT id FROM {quantity} WHERE type = 'price'")->fetchCol();

    // Install the new total_price field.
    $update_manager = \Drupal::entityDefinitionUpdateManager();
    $options = [
      'type' => 'fraction',
      'label' => t('Total price'),
      'description' => t('The total price. If left blank, this will be automatically calculated.'),
      'weight' => [
        'form' => 12,
        'view' => 12,
      ],
    ];
    $field_definition = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
    $update_manager->installFieldStorageDefinition('total_price', 'quantity', 'farm_ledger', $field_definition);

    // Track progress.
    $sandbox['current_quantity'] = 0;
    $sandbox['#finished'] = 0;
  }

  // Get the active database connection.
  $database = \Drupal::database();

  // Iterate through price quantities, 10 at a time.
  $quantity_count = count($sandbox['price_quantity_ids']);
  $end_quantity = $sandbox['current_quantity'] + 10;
  $end_quantity = $end_quantity > $quantity_count ? $quantity_count : $end_quantity;
  for ($i = $sandbox['current_quantity']; $i < $end_quantity; $i++) {

    // Iterate the global counter.
    $sandbox['current_quantity']++;

    // Get the quantity ID.
    $id = $sandbox['price_quantity_ids'][$i];

    // Migrate value to total_price, and unit_quantity to value. We use raw
    // database queries for this to avoid creating a new quantity revision,
    // because the old revision would have missing data.
    $database->query("INSERT INTO {quantity__total_price} (SELECT type as bundle, 0 as deleted, id as entity_id, revision_id, 'und' as langcode, 0 as delta, value__numerator as total_price_numerator, value__denominator as total_price_denominator FROM {quantity} WHERE id = :id)", [':id' => $id]);
    $database->query("INSERT INTO {quantity_revision__total_price} (SELECT type as bundle, 0 as deleted, id as entity_id, revision_id, 'und' as langcode, 0 as delta, value__numerator as total_price_numerator, value__denominator as total_price_denominator FROM {quantity} WHERE id = :id)", [':id' => $id]);
    $database->query("UPDATE {quantity} q SET value__numerator = (SELECT unit_quantity_numerator FROM {quantity__unit_quantity} uq WHERE uq.entity_id = q.id), value__denominator = (SELECT unit_quantity_denominator FROM {quantity__unit_quantity} uq WHERE uq.entity_id = q.id) WHERE q.id = :id", [':id' => $id]);
    $database->query("UPDATE {quantity_revision} qr SET value__numerator = (SELECT unit_quantity_numerator FROM {quantity_revision__unit_quantity} uq WHERE uq.entity_id = qr.id AND uq.revision_id = qr.revision_id), value__denominator = (SELECT unit_quantity_denominator FROM {quantity_revision__unit_quantity} uq WHERE uq.entity_id = qr.id AND uq.revision_id = qr.revision_id) WHERE qr.id = :id", [':id' => $id]);
  }

  // Update progress. If there are no price quantities, we're finished.
  if (!empty($sandbox['price_quantity_ids'])) {
    $sandbox['#finished'] = $sandbox['current_quantity'] / count($sandbox['price_quantity_ids']);
  }
  else {
    $sandbox['#finished'] = 1;
  }

  // When we are finished, delete the unit_quantity field.
  if ($sandbox['#finished'] == 1) {
    $update_manager = \Drupal::entityDefinitionUpdateManager();
    $storage_definition = $update_manager->getFieldStorageDefinition('unit_quantity', 'quantity');
    $update_manager->uninstallFieldStorageDefinition($storage_definition);
  }

  return NULL;
}

/**
 * Uninstall v1 migration configs.
 */
function farm_ledger_post_update_uninstall_v1_migrations(&$sandbox) {
  $config = \Drupal::configFactory()->getEditable('migrate_plus.migration.farm_migrate_log_sale');
  if (!empty($config)) {
    $config->delete();
  }
  $config = \Drupal::configFactory()->getEditable('migrate_plus.migration.farm_migrate_log_purchase');
  if (!empty($config)) {
    $config->delete();
  }
}
