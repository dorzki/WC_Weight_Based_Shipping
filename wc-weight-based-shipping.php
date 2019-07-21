<?php
/**
 * Adds a new shipping method to calculate the price based on the total weight of an order.
 *
 * Plugin Name: WooCommerce Weight Based Shipping
 * Plugin URI: https://www.dorzki.co.il/calculating-shipping-based-on-order-total-weight-woocommerce/
 * Description: Adds a new shipping method to calculate the price based on the total weight of an order.
 * Version: 1.0.0
 * Author: dorzki
 * Author URI: https://www.dorzki.co.il
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: dorzki-wc-weight-shipping
 *
 * @package    WordPress
 * @subpackage Plugins
 * @author     Dor Zuberi <webmaster@dorzki.co.il>
 * @link       https://www.dorzki.co.il
 * @version    1.0.0
 */

// Block if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'DZ_WC_WEIGHT_SHIP_PATH', plugin_dir_path( __FILE__ ) );


/**
 * Enable support for plugin localization and internationalization.
 */
function dorzki_wc_weight_shipping_register_plugin_i18n() {

	load_plugin_textdomain( 'dorzki-wc-weight-shipping', false, basename( dirname( __FILE__ ) ) . '/languages/' );

}

add_action( 'plugins_loaded', 'dorzki_wc_weight_shipping_register_plugin_i18n' );


/**
 * Checks if WooCommerce is installed and activated.
 *
 * @return bool
 */
function dorzki_wc_weight_shipping_check_required_plugins() {

	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

		add_action( 'admin_notices', 'dorzki_wc_weight_shipping_display_woocommerce_notice' );

		return false;

	}

	include_once 'class-plugin.php';

	return true;

}

add_action( 'plugins_loaded', 'dorzki_wc_weight_shipping_check_required_plugins' );


/**
 * Displays an admin notice for WooCommerce.
 */
function dorzki_wc_weight_shipping_display_woocommerce_notice() {

	$notice = sprintf(
	/* translators: 1: WooCommerce 2: Plugin Name */
		esc_html__( '"%1$s" is required to be installed and activated in order to use "%2$s".', 'dorzki-wc-weight-shipping' ),
		'<strong>' . esc_html__( 'WooCommerce', 'dorzki-wc-weight-shipping' ) . '</strong>',
		'<strong>' . esc_html__( 'WooCommerce Weight Based Shipping', 'dorzki-wc-weight-shipping' ) . '</strong>'
	);

	echo "<div class='notice notice-error'><p>{$notice}</p></div>";

}