<?php
/**
 * Plugin Name:       WooCommerce Delivery Date Estimator
 * Description:       Displays an estimated delivery range on WooCommerce product, cart, and checkout pages.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Nikita Patel
 * Author URI:        https://nikita-portfolio-navy.vercel.app/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       woo-delivery-date-estimator
 * Requires Plugins:  woocommerce
 *
 * @package WooDeliveryDateEstimator
 */

defined( 'ABSPATH' ) || exit;

define( 'WDDE_VERSION', '1.0.0' );
define( 'WDDE_PLUGIN_FILE', __FILE__ );
define( 'WDDE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WDDE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WDDE_PLUGIN_PATH . 'includes/class-wdde-date-calculator.php';
require_once WDDE_PLUGIN_PATH . 'includes/class-wdde-settings.php';
require_once WDDE_PLUGIN_PATH . 'includes/class-wdde-frontend.php';
require_once WDDE_PLUGIN_PATH . 'includes/class-wdde-plugin.php';

/**
 * Start the plugin after all active plugins have loaded.
 */
function wdde_initialize_plugin() {
	WDDE_Plugin::instance();
}

add_action( 'plugins_loaded', 'wdde_initialize_plugin' );