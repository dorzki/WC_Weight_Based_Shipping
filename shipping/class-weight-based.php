<?php
/**
 * Class Weight_Based
 *
 * @package    dorzki\WooCommerce\Weight_Shipping\Shipping
 * @subpackage Weight_Based
 * @author     Dor Zuberi <webmaster@dorzki.co.il>
 * @link       https://www.dorzki.co.il
 * @version    1.0.0
 */

namespace dorzki\WooCommerce\Weight_Shipping\Shipping;

use WC_Shipping_Method;

// Block if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class Weight_Based
 *
 * @package dorzki\WooCommerce\Weight_Shipping\Shipping
 */
class Weight_Based extends WC_Shipping_Method {

	/**
	 * Weight_Based constructor.
	 *
	 * @param int $instance_id shipping method instance number.
	 */
	public function __construct( $instance_id = 0 ) {

		$this->id          = 'weight_based';
		$this->instance_id = absint( $instance_id );

		$this->method_title       = __( 'Weight Based', 'dorzki-wc-weight-shipping' );
		$this->method_description = __( 'Calculate shipping based on order weight.', 'dorzki-wc-weight-shipping' );

		$this->supports = [ 'shipping-zones', 'instance-settings', 'instance-settings-modal' ];

		$this->register_shipping_fields();

		$this->title       = $this->get_option( 'title' );
		$this->tax_status  = $this->get_option( 'tax_status' );
		$this->base_cost   = (float) $this->get_option( 'base_cost' );
		$this->weight_unit = (float) $this->get_option( 'weight_unit' );
		$this->unit_cost   = (float) $this->get_option( 'unit_cost' );

		add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );

	}


	/* ------------------------------------------ */


	/**
	 * Register shipping method settings.
	 */
	private function register_shipping_fields() {

		$this->instance_form_fields = [
			'title'       => [
				'title'       => __( 'Method Title', 'dorzki-wc-weight-shipping' ),
				'type'        => 'text',
				'description' => __( 'The shipping title, will be visible to the user.', 'dorzki-wc-weight-shipping' ),
				'default'     => __( 'Weight Based Shipping', 'dorzki-wc-weight-shipping' ),
				'desc_tip'    => true,
			],
			'tax_status'  => [
				'title'   => __( 'Taxes', 'dorzki-wc-weight-shipping' ),
				'type'    => 'select',
				'class'   => 'wc-enhanced-select',
				'default' => 'taxable',
				'options' => [
					'taxable' => __( 'Add Tax', 'dorzki-wc-weight-shipping' ),
					'none'    => __( 'Tax Free', 'dorzki-wc-weight-shipping' ),
				],
			],
			'base_cost'   => [
				'title'       => __( 'Base Cost', 'dorzki-wc-weight-shipping' ),
				'type'        => 'text',
				'description' => __( 'Base cost for shipping, leave empty or 0 for none.', 'dorzki-wc-weight-shipping' ),
				'default'     => 0,
				'desc_tip'    => true,
			],
			'weight_unit' => [
				'title'       => __( 'Weight Unit', 'dorzki-wc-weight-shipping' ),
				'type'        => 'text',
				'description' => __( 'Enter the weight unit in kg to base the calculation (for example: 0.1).', 'dorzki-wc-weight-shipping' ),
				'default'     => 0,
				'desc_tip'    => true,
			],
			'unit_cost'   => [
				'title'       => __( 'Unit Cost', 'dorzki-wc-weight-shipping' ),
				'type'        => 'text',
				'description' => __( 'Enter the price to multiply with "Weight Unit" for shipping calculation.', 'dorzki-wc-weight-shipping' ),
				'default'     => 0,
				'desc_tip'    => true,
			],
		];

	}


	/* ------------------------------------------ */


	/**
	 * Calculate shipping based on order total weight.
	 *
	 * @param array $package cart contents.
	 */
	public function calculate_shipping( $package = [] ) {

		$method = [
			'id'      => $this->get_rate_id(),
			'label'   => $this->title,
			'cost'    => floatval( $this->base_cost ),
			'package' => $package,
		];

		$total_weight = 0;

		// Calculate weight.
		foreach ( $package['contents'] as $cart_product ) {
			$total_weight += floatval( $cart_product['data']->get_weight() * $cart_product['quantity'] );
		}

		$method['cost'] += floatval( ( $total_weight / $this->weight_unit ) * $this->unit_cost );

		$this->add_rate( $method );

	}

}