<?php
/**
 * Plugin Name: User Verification and Discounts
 * Description: Instantly verify millions of academic users and provide them with a discount for products on your store.
 * Version: 1.0.0
 * Requires at least: 3.0
 * Requires PHP: 7.0
 * Author:Proxi.id Corp
 * Author URI: https://www.proxi.id
 * Text Domain: user-verification-and-discounts
 * Domain Path: /languages
 *
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @package extension
 */

defined('ABSPATH') || exit;

if (! defined('MAIN_PLUGIN_FILE')) {
    define('MAIN_PLUGIN_FILE', __FILE__);
}

require_once( plugin_dir_path( __FILE__ ) . 'settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'buttons.php' );

use ProxiIdVerification\Admin\Setup;

/**
 * WooCommerce fallback notice.
 *
 * @since 0.1.0
 */

register_activation_hook(__FILE__, 'uvd_plugin_activate');
register_deactivation_hook(__FILE__, 'uvd_plugin_deactivate');
register_uninstall_hook(__FILE__, 'uvd_plugin_uninstall');

// Remove the option created by plugin if the plugin is uninstalled by admin
function uvd_plugin_uninstall(){
    delete_option( 'uvd-plugin-settings' );
}

/**
 * Activation hook.
 *
 * @since 0.1.0
 */
function uvd_plugin_activate()
{
    if ( !get_option( 'uvd-plugin-settings' ) ) {
        // Declare the option with a default value
        $default_value = array(
            'api_key' => '',
            'discount_value' => 10,
            'discount_type' => 'percentage',
            'button_text' => 'Check eligibility',
            'button_pretext' => 'Students get 10% off',
            'notice_link_text' => 'Click here to get a student discount on items in your cart!',
            'ui_elements_checkbox' => 'true',
            'button_url' => '',
            'config_setting' => 'disabled',
            'discount_category' => '',
            'response_code' => 0
        );
        add_option( 'uvd-plugin-settings', $default_value );
    }
}
function uvd_plugin_deactivate()
{
    // remove_submenu_page('tools.php', 'user-verification-and-discounts');

    $settings = get_option('uvd-plugin-settings');
    $settings['discount_value'] = 0;
    update_option('uvd-plugin-settings', $settings);
    uvd_plugin_get_url();

    if (str_contains(get_site_url(), 'localhost')){
        delete_option( 'uvd-plugin-settings' );
    }


}

if (! defined('PROXI_ID_VERIFY_ABSPATH')) {
    define('PROXI_ID_VERIFY_HELPER_ABSPATH', dirname(__FILE__) . '/');
}

if (! class_exists('uvd_plugin')) :
    /**
     * The uvd_plugin class.
     */
    class uvd_plugin
    {
        /**
         * This class instance.
         *
         * @var \uvd_plugin single instance of this class.
         */
        private static $instance;

        /**
         * Constructor.
         */

        /**
         * Cloning is forbidden.
         */
        public function __clone()
        {
            wc_doing_it_wrong(__FUNCTION__, __('Cloning is forbidden.', 'uvd_plugin'), $this->version);
        }

        /**
         * Unserializing instances of this class is forbidden.
         */
        public function __wakeup()
        {
            wc_doing_it_wrong(__FUNCTION__, __('Unserializing instances of this class is forbidden.', 'uvd_plugin'), $this->version);
        }

        /**
         * Gets the main instance.
         *
         * Ensures only one instance can be loaded.
         *
         * @return \uvd_plugin
         */
        public static function instance()
        {
            if (null === self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }
    }
endif;

add_action('plugins_loaded', 'uvd_plugin_init', 10);

/**
 * Initialize the plugin.
 *
 * @since 0.1.0
 */
function uvd_plugin_init()
{
    load_plugin_textdomain('uvd_plugin', false, plugin_basename(dirname(__FILE__)) . '/languages');

    // if (! class_exists('WooCommerce')) {
    //     add_action('admin_notices', 'uvd_plugin_missing_wc_notice');
    //     return;
    // }

    uvd_plugin::instance();
}

function proxi_id_woocommerce_apply_cart_coupon_in_url() {
        // Return early if WooCommerce or sessions aren't available.
        if ( ! function_exists( 'WC' ) || ! WC()->session ) {
                return;
        }

        // Return if there is no coupon in the URL, otherwise set the variable.
        if ( empty( $_REQUEST['coupon-code'] ) ) {
                return;
        } else {
                $coupon_code = esc_attr( $_REQUEST['coupon-code'] );
        }

        // Set a session cookie to remember the coupon if they continue shopping.
        WC()->session->set_customer_session_cookie(true);

        // Apply the coupon to the cart if necessary.
        if ( ! WC()->cart->has_discount( $coupon_code ) ) {

                // WC_Cart::add_discount() sanitizes the coupon code.
                WC()->cart->add_discount( $coupon_code );
        }
}
add_action('wp_loaded', 'proxi_id_woocommerce_apply_cart_coupon_in_url', 30);
add_action('woocommerce_add_to_cart', 'proxi_id_woocommerce_apply_cart_coupon_in_url');


