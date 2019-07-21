<?php
/**
 * Main plugin file.
 *
 * @package    dorzki\WooCommerce\Weight_Shipping
 * @subpackage Plugin
 * @author     Dor Zuberi <webmaster@dorzki.co.il>
 * @link       https://www.dorzki.co.il
 * @version    1.0.0
 */

namespace dorzki\WooCommerce\Weight_Shipping;

// Block if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class Plugin
 *
 * @package dorzki\WooCommerce\Weight_Shipping
 */
class Plugin {

	/**
	 * Plugin instance.
	 *
	 * @var null|Plugin
	 */
	private static $instance = null;


	/* ------------------------------------------ */


	/**
	 * Plugin constructor.
	 */
	public function __construct() {

		add_filter( 'woocommerce_shipping_methods', [ $this, 'register_shipping_types' ] );

	}


	/* ------------------------------------------ */


	/**
	 * Retrieve plugin instance.
	 *
	 * @return Plugin|null
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();

		}

		return self::$instance;

	}


	/* ------------------------------------------ */


	/**
	 * Register new shipping types.
	 *
	 * @param array $shipping_types woocommerce registered shipping types.
	 *
	 * @return array
	 */
	public function register_shipping_types( $shipping_types ) {

		require_once DZ_WC_WEIGHT_SHIP_PATH . "shipping/class-weight-based.php";

		$shipping_types['weight_based'] = 'dorzki\WooCommerce\Weight_Shipping\Shipping\Weight_Based';

		return $shipping_types;

	}

}

// initiate plugin.
Plugin::get_instance();
