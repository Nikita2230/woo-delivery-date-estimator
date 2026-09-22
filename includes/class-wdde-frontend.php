<?php
/**
 * Frontend delivery-estimate output.
 *
 * @package WooDeliveryDateEstimator
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handle frontend output and WooCommerce integrations.
 */
final class WDDE_Frontend {

	/**
	 * Register frontend hooks.
	 */
	public function __construct() {
		// Classic WooCommerce templates.
		add_action(
			'woocommerce_single_product_summary',
			array( $this, 'display_estimate' ),
			25
		);

		add_action(
			'woocommerce_before_cart_totals',
			array( $this, 'display_estimate' )
		);

		add_action(
			'woocommerce_review_order_before_payment',
			array( $this, 'display_estimate' )
		);

		// WooCommerce blocks.
		add_filter(
			'render_block_woocommerce/cart',
			array( $this, 'add_estimate_to_cart_block' )
		);

		add_filter(
			'render_block_woocommerce/checkout',
			array( $this, 'add_estimate_to_checkout_block' )
		);

		add_action(
			'wp_enqueue_scripts',
			array( $this, 'enqueue_styles' )
		);
	}

	/**
	 * Print the delivery estimate.
	 */
	public function display_estimate() {
		echo wp_kses_post( $this->get_estimate_html() );
	}

	/**
	 * Return the delivery-estimate HTML.
	 *
	 * @return string
	 */
	public function get_estimate_html() {
		$settings = WDDE_Settings::get_settings();

		if ( ! $this->should_display( $settings ) ) {
			return '';
		}

		$current_date = new DateTimeImmutable(
			'now',
			wp_timezone()
		);

		$current_hour = (int) $current_date->format( 'G' );
		$current_day  = (int) $current_date->format( 'N' );
		$cutoff_hour  = absint( $settings['cutoff_hour'] );

		$is_after_cutoff = $current_hour >= $cutoff_hour;
		$is_weekend      = $current_day >= 6;

		$calculation_date = $current_date;

		if ( $is_after_cutoff || $is_weekend ) {
			$calculation_date = WDDE_Date_Calculator::add_business_days(
				$current_date,
				1
			);
		}

		$minimum_days =
			absint( $settings['handling_days'] ) +
			absint( $settings['minimum_days'] );

		$maximum_days =
			absint( $settings['handling_days'] ) +
			absint( $settings['maximum_days'] );

		$earliest_date = WDDE_Date_Calculator::add_business_days(
			$calculation_date,
			$minimum_days
		);

		$latest_date = WDDE_Date_Calculator::add_business_days(
			$calculation_date,
			$maximum_days
		);

		$date_format = get_option( 'date_format' );
		$timezone    = wp_timezone();

		$earliest_text = wp_date(
			$date_format,
			$earliest_date->getTimestamp(),
			$timezone
		);

		$latest_text = wp_date(
			$date_format,
			$latest_date->getTimestamp(),
			$timezone
		);

		$message = sprintf(
			/* translators: 1: earliest date, 2: latest date. */
			__(
				'Estimated delivery: %1$s–%2$s',
				'woo-delivery-date-estimator'
			),
			$earliest_text,
			$latest_text
		);

		return sprintf(
			'<div class="wdde-delivery-estimate" role="status">
				<span class="wdde-delivery-estimate__icon" aria-hidden="true">🚚</span>
				<span>%s</span>
			</div>',
			esc_html( $message )
		);
	}

	/**
	 * Determine whether the estimate should appear.
	 *
	 * @param array $settings Plugin settings.
	 *
	 * @return bool
	 */
	private function should_display( $settings ) {
		if ( 'yes' !== $settings['enabled'] ) {
			return false;
		}

		if (
			function_exists( 'is_product' ) &&
			is_product() &&
			'yes' !== $settings['show_product']
		) {
			return false;
		}

		if (
			function_exists( 'is_cart' ) &&
			is_cart() &&
			'yes' !== $settings['show_cart']
		) {
			return false;
		}

		if (
			function_exists( 'is_checkout' ) &&
			is_checkout() &&
			'yes' !== $settings['show_checkout']
		) {
			return false;
		}

		return true;
	}

	/**
	 * Add the estimate before the Cart block.
	 *
	 * @param string $block_content Rendered block HTML.
	 *
	 * @return string
	 */
	public function add_estimate_to_cart_block( $block_content ) {
		if ( is_admin() ) {
			return $block_content;
		}

		return $this->get_estimate_html() . $block_content;
	}

	/**
	 * Add the estimate before the Checkout block.
	 *
	 * @param string $block_content Rendered block HTML.
	 *
	 * @return string
	 */
	public function add_estimate_to_checkout_block( $block_content ) {
		if ( is_admin() ) {
			return $block_content;
		}

		return $this->get_estimate_html() . $block_content;
	}

	/**
	 * Load the frontend stylesheet where required.
	 */
	public function enqueue_styles() {
		if (
			! function_exists( 'is_product' ) ||
			! function_exists( 'is_cart' ) ||
			! function_exists( 'is_checkout' )
		) {
			return;
		}

		if ( ! is_product() && ! is_cart() && ! is_checkout() ) {
			return;
		}

		wp_enqueue_style(
			'wdde-delivery-estimate',
			WDDE_PLUGIN_URL . 'assets/css/delivery-estimate.css',
			array(),
			WDDE_VERSION
		);
	}
}
