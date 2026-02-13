<?php

/**
 * @file
 * Post update hooks for the farm_ledger module.
 */

/**
 * Implements hook_removed_post_updates().
 */
function farm_ledger_removed_post_updates() {
  return [
    'farm_ledger_post_update_migrate_price_quantity_total_price' => '3.x',
    'farm_ledger_post_update_uninstall_v1_migrations' => '3.x',
    'farm_ledger_post_update_farm_log_workflow' => '3.x',
  ];
}
