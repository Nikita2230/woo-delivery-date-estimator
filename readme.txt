=== WooCommerce Delivery Date Estimator ===
Contributors: nikitapatel
Tags: woocommerce, delivery date, shipping estimate, ecommerce
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display an estimated delivery range on WooCommerce product, cart, and checkout pages.

== Description ==

WooCommerce Delivery Date Estimator helps customers understand when their orders may arrive before completing a purchase.

The estimate is calculated using configurable handling time, minimum and maximum transit times, weekends, and a daily order cutoff.

Features:

* Product-page delivery estimates
* Classic cart and checkout support
* WooCommerce Cart and Checkout block support
* Configurable handling days
* Configurable minimum and maximum transit days
* Configurable order cutoff hour
* Automatic weekend exclusion
* WordPress timezone support
* Global enable or disable control
* Individual product, cart, and checkout visibility controls
* No external APIs or visitor tracking

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install its ZIP through the WordPress Plugins screen.
2. Activate WooCommerce Delivery Date Estimator.
3. Open WooCommerce > Delivery Estimate.
4. Configure the delivery calculation settings.
5. Save the settings.

== Frequently Asked Questions ==

= Does the plugin require WooCommerce? =

Yes. WooCommerce must be installed and active.

= Does it support the Cart and Checkout blocks? =

Yes. It supports both modern WooCommerce blocks and classic shortcode templates.

= Does it skip weekends? =

Yes. Saturdays and Sundays are excluded from delivery calculations.

= Does it account for public holidays? =

Version 1.0.0 does not exclude public holidays. Holiday support may be added in a future release.

= Does it connect to a courier API? =

No. The plugin calculates a store-level estimate using the settings configured by the store administrator.

= Does it collect customer information? =

No. The plugin does not collect personal data, create cookies, or make external requests.

== Changelog ==

= 1.0.0 =

* Initial release.
* Added product-page delivery estimates.
* Added classic cart and checkout support.
* Added WooCommerce Cart and Checkout block support.
* Added configurable handling and transit times.
* Added daily order cutoff.
* Added weekend exclusion.
* Added display-location controls.
* Added WooCommerce dependency handling.
