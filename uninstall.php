<?php
/**
 * Plugin uninstall cleanup.
 *
 * @package WooDeliveryDateEstimator
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Remove plugin settings.
 */
delete_option( 'wdde_settings' );
