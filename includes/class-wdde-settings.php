<?php
/**
 * Plugin settings and administration.
 *
 * @package WooDeliveryDateEstimator
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manage plugin settings.
 */
final class WDDE_Settings {

	/**
	 * Database option name.
	 */
	const OPTION_NAME = 'wdde_settings';

	/**
	 * Settings page slug.
	 */
	const PAGE_SLUG = 'wdde-settings';

	/**
	 * Register WordPress hooks.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Return default settings.
	 *
	 * @return array
	 */
	public static function get_defaults() {
		return array(
			'enabled'       => 'yes',
			'show_product'  => 'yes',
			'show_cart'     => 'yes',
			'show_checkout' => 'yes',
			'handling_days' => 1,
			'minimum_days'  => 2,
			'maximum_days'  => 5,
			'cutoff_hour'   => 14,
		);
	}

	/**
	 * Return saved settings merged with defaults.
	 *
	 * @return array
	 */
	public static function get_settings() {
		return wp_parse_args(
			get_option( self::OPTION_NAME, array() ),
			self::get_defaults()
		);
	}

	/**
	 * Add the settings page under WooCommerce.
	 */
	public function add_settings_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Delivery Estimate', 'woo-delivery-date-estimator' ),
			__( 'Delivery Estimate', 'woo-delivery-date-estimator' ),
			'manage_woocommerce',
			self::PAGE_SLUG,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings, sections, and fields.
	 */
	public function register_settings() {
		register_setting(
			'wdde_settings_group',
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => self::get_defaults(),
			)
		);

		add_settings_section(
			'wdde_delivery_section',
			__( 'Delivery calculation', 'woo-delivery-date-estimator' ),
			array( $this, 'render_section' ),
			self::PAGE_SLUG
		);

		$checkboxes = array(
			'enabled' => array(
				'label' => __( 'Enable delivery estimate', 'woo-delivery-date-estimator' ),
				'text'  => __( 'Show estimated delivery dates to customers', 'woo-delivery-date-estimator' ),
			),
			'show_product' => array(
				'label' => __( 'Show on product pages', 'woo-delivery-date-estimator' ),
				'text'  => __( 'Display the estimate on product pages', 'woo-delivery-date-estimator' ),
			),
			'show_cart' => array(
				'label' => __( 'Show on the cart page', 'woo-delivery-date-estimator' ),
				'text'  => __( 'Display the estimate on the cart page', 'woo-delivery-date-estimator' ),
			),
			'show_checkout' => array(
				'label' => __( 'Show on the checkout page', 'woo-delivery-date-estimator' ),
				'text'  => __( 'Display the estimate on the checkout page', 'woo-delivery-date-estimator' ),
			),
		);

		foreach ( $checkboxes as $key => $field ) {
			add_settings_field(
				'wdde_' . $key,
				$field['label'],
				array( $this, 'render_checkbox' ),
				self::PAGE_SLUG,
				'wdde_delivery_section',
				array(
					'key'  => $key,
					'text' => $field['text'],
				)
			);
		}

		$numbers = array(
			'handling_days' => array(
				'label' => __( 'Handling days', 'woo-delivery-date-estimator' ),
				'max'   => 60,
			),
			'minimum_days' => array(
				'label' => __( 'Minimum transit days', 'woo-delivery-date-estimator' ),
				'max'   => 60,
			),
			'maximum_days' => array(
				'label' => __( 'Maximum transit days', 'woo-delivery-date-estimator' ),
				'max'   => 60,
			),
			'cutoff_hour' => array(
				'label'       => __( 'Order cutoff hour', 'woo-delivery-date-estimator' ),
				'max'         => 23,
				'description' => __( 'Use 24-hour format. For example, 14 means 2:00 PM.', 'woo-delivery-date-estimator' ),
			),
		);

		foreach ( $numbers as $key => $field ) {
			add_settings_field(
				'wdde_' . $key,
				$field['label'],
				array( $this, 'render_number' ),
				self::PAGE_SLUG,
				'wdde_delivery_section',
				array(
					'key'         => $key,
					'max'         => $field['max'],
					'description' => isset( $field['description'] )
						? $field['description']
						: '',
				)
			);
		}
	}

	/**
	 * Render the settings-section description.
	 */
	public function render_section() {
		?>
		<p>
			<?php
			esc_html_e(
				'Configure the business days used to calculate delivery estimates.',
				'woo-delivery-date-estimator'
			);
			?>
		</p>
		<?php
	}

	/**
	 * Render a checkbox.
	 *
	 * @param array $args Field configuration.
	 */
	public function render_checkbox( $args ) {
		$settings = self::get_settings();
		$key      = $args['key'];
		?>
		<label>
			<input
				type="checkbox"
				name="<?php echo esc_attr( self::OPTION_NAME . '[' . $key . ']' ); ?>"
				value="yes"
				<?php checked( $settings[ $key ], 'yes' ); ?>
			/>
			<?php echo esc_html( $args['text'] ); ?>
		</label>
		<?php
	}

	/**
	 * Render a number field.
	 *
	 * @param array $args Field configuration.
	 */
	public function render_number( $args ) {
		$settings = self::get_settings();
		$key      = $args['key'];
		?>
		<input
			type="number"
			name="<?php echo esc_attr( self::OPTION_NAME . '[' . $key . ']' ); ?>"
			value="<?php echo esc_attr( absint( $settings[ $key ] ) ); ?>"
			min="0"
			max="<?php echo esc_attr( $args['max'] ); ?>"
			step="1"
			class="small-text"
		/>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description">
				<?php echo esc_html( $args['description'] ); ?>
			</p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Sanitize submitted settings.
	 *
	 * @param mixed $input Submitted data.
	 *
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		$input    = is_array( $input ) ? $input : array();
		$defaults = self::get_defaults();

		$minimum_days = isset( $input['minimum_days'] )
			? min( absint( $input['minimum_days'] ), 60 )
			: $defaults['minimum_days'];

		$maximum_days = isset( $input['maximum_days'] )
			? min( absint( $input['maximum_days'] ), 60 )
			: $defaults['maximum_days'];

		$maximum_days = max( $minimum_days, $maximum_days );

		return array(
			'enabled'       => isset( $input['enabled'] ) ? 'yes' : 'no',
			'show_product'  => isset( $input['show_product'] ) ? 'yes' : 'no',
			'show_cart'     => isset( $input['show_cart'] ) ? 'yes' : 'no',
			'show_checkout' => isset( $input['show_checkout'] ) ? 'yes' : 'no',
			'handling_days' => isset( $input['handling_days'] )
				? min( absint( $input['handling_days'] ), 60 )
				: $defaults['handling_days'],
			'minimum_days'  => $minimum_days,
			'maximum_days'  => $maximum_days,
			'cutoff_hour'   => isset( $input['cutoff_hour'] )
				? min( absint( $input['cutoff_hour'] ), 23 )
				: $defaults['cutoff_hour'],
		);
	}

	/**
	 * Render the settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1>
				<?php
				esc_html_e(
					'WooCommerce Delivery Date Estimator',
					'woo-delivery-date-estimator'
				);
				?>
			</h1>

			<form action="options.php" method="post">
				<?php
				settings_fields( 'wdde_settings_group' );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
