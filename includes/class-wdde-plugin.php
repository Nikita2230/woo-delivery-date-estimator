<?php
/**
 * Main plugin controller.
 *
 * @package WooDeliveryDateEstimator
 */

defined( 'ABSPATH' ) || exit;

/**
 * Initialize the plugin and its components.
 */
final class WDDE_Plugin {

	/**
	 * Plugin instance.
	 *
	 * @var WDDE_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Return the plugin instance.
	 *
	 * @return WDDE_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize plugin components.
	 */
	private function __construct() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action(
				'admin_notices',
				array( $this, 'render_dependency_notice' )
			);

			return;
		}

		new WDDE_Settings();
		new WDDE_Frontend();
	}

	/**
	 * Prevent cloning the singleton.
	 */
	private function __clone() {}

	/**
	 * Display the missing WooCommerce notice.
	 */
	public function render_dependency_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		?>
		<div class="notice notice-error">
			<p>
				<?php
				esc_html_e(
					'WooCommerce Delivery Date Estimator requires WooCommerce to be installed and active.',
					'woo-delivery-date-estimator'
				);
				?>
			</p>
		</div>
		<?php
	}
}