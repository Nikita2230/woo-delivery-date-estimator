# WooCommerce Delivery Date Estimator

[![License: GPL v2 or later](https://img.shields.io/badge/License-GPL_v2_or_later-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

A lightweight WooCommerce plugin that displays an estimated delivery range on product, cart, and checkout pages.

It helps shoppers answer a common pre-purchase question:

> When will my order arrive?

## Features

- Delivery estimates on product pages
- Classic cart and checkout support
- WooCommerce Cart and Checkout block support
- Configurable handling time
- Configurable minimum and maximum transit days
- Configurable daily order cutoff
- Automatic weekend exclusion
- WordPress store-timezone support
- Global enable/disable control
- Individual product, cart, and checkout visibility controls
- Clean uninstall process
- No external APIs or visitor tracking

## Requirements

- WordPress 6.4 or newer
- PHP 7.4 or newer
- WooCommerce installed and activated

## Installation

1. Download the plugin ZIP.
2. Open **WordPress Admin → Plugins → Add New Plugin**.
3. Select **Upload Plugin**.
4. Upload the ZIP file.
5. Activate **WooCommerce Delivery Date Estimator**.
6. Open **WooCommerce → Delivery Estimate**.
7. Configure and save the delivery settings.

## Settings

| Setting | Description |
|---|---|
| Enable delivery estimate | Globally enables or disables frontend output |
| Show on product pages | Controls product-page visibility |
| Show on cart page | Controls cart visibility |
| Show on checkout page | Controls checkout visibility |
| Handling days | Store processing time in business days |
| Minimum transit days | Earliest transit duration |
| Maximum transit days | Latest transit duration |
| Order cutoff hour | Moves late orders to the next business day |

The cutoff uses 24-hour time. For example, `14` means 2:00 PM.

## Delivery calculation

The delivery range is calculated using:

```text
Handling days + Transit days
```

Saturdays and Sundays are skipped.

If an order is placed after the configured cutoff or during a weekend, the calculation begins from the next business day.

Example:

```text
Handling time:         1 business day
Minimum transit time:  2 business days
Maximum transit time:  5 business days
Estimated range:       3–6 business days
```

## Project structure

```text
woo-delivery-date-estimator/
├── assets/
│   └── css/
│       └── delivery-estimate.css
├── includes/
│   ├── class-wdde-date-calculator.php
│   ├── class-wdde-frontend.php
│   ├── class-wdde-plugin.php
│   └── class-wdde-settings.php
├── .gitignore
├── LICENSE
├── README.md
├── readme.txt
├── uninstall.php
└── woo-delivery-date-estimator.php
```

## Architecture

The plugin separates responsibilities into focused classes:

- `WDDE_Plugin` initializes the plugin and checks the WooCommerce dependency.
- `WDDE_Settings` registers, sanitizes, and renders administration settings.
- `WDDE_Date_Calculator` performs business-day calculations.
- `WDDE_Frontend` handles WooCommerce hooks, blocks, frontend output, and styles.

## Supported WooCommerce views

- Single-product templates
- Classic cart shortcode
- Cart block
- Classic checkout shortcode
- Checkout block

## Privacy

This plugin:

- Does not collect personal information
- Does not create cookies
- Does not contact external services
- Does not track visitors
- Stores only administrator-configured settings

## Roadmap

- Product-specific handling times
- Public-holiday exclusions
- Shipping-zone-specific estimates
- Customizable frontend message
- Custom colors and icons
- Automated calculation tests
- Translation files

## Contributing

Issues and pull requests are welcome.

When reporting a problem, include:

- WordPress version
- WooCommerce version
- PHP version
- Active theme
- Whether Cart and Checkout use blocks or classic shortcodes

## License

Licensed under the [GNU General Public License v2.0 or later](https://www.gnu.org/licenses/gpl-2.0.html).

## Author

**Nikita Patel**  
WordPress and WooCommerce Developer

[Portfolio](https://nikita-portfolio-navy.vercel.app/)