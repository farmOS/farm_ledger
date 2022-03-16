# farmOS Ledger

Provides sale and purchase log types for farmOS.

This module is an add-on for the [farmOS](http://drupal.org/project/farm)
distribution.

## Log types

### Purchase Logs

Purchase Logs have the following additional attributes:

- Invoice number (string)
- Seller (string)
- Lot number (string)

### Sale Logs

Sale Logs have the following additional attributes:

- Customer (string)
- Invoice number (string)
- Lot number (string)

## Quantity types

### Price Quantity

Price Quantities have additional "Unit Price" and "Unit Quantity" attributes,
alongside the standard "Value" attribute. When these are used, the "Value"
attribute is considered to be the total value ("Unit Price" multiplied by
"Unit Quantity"). The "Units" of a Price Quantity will generally be a
currency code such as "USD" or "EUR".

## Installation

Install as you would normally install a contributed drupal module. See:
https://www.drupal.org/docs/extending-drupal/installing-modules for further
information.

## Maintainers

Current maintainers:
* Michael Stenta (m.stenta) - https://github.com/mstenta

This project has been sponsored by:
* [Farmier](http://farmier.com)
