<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codeboxr.com
 * @since             1.0.0
 * @package           CBXMCRatingReview
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Multi Criteria Rating & Review
 * Plugin URI:        https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/
 * Description:       Multi Criteria Rating & Review System for WordPress
 * Version:           2.0.2
 * Author:            Codeboxr
 * Author URI:        https://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxmcratingreview
 * Domain Path:       /languages
 */

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


defined( 'CBXMCRATINGREVIEW_PLUGIN_NAME' ) or define( 'CBXMCRATINGREVIEW_PLUGIN_NAME', 'cbxmcratingreview' );
defined( 'CBXMCRATINGREVIEW_PLUGIN_VERSION' ) or define( 'CBXMCRATINGREVIEW_PLUGIN_VERSION', '2.0.2' );
defined( 'CBXMCRATINGREVIEW_BASE_NAME' ) or define( 'CBXMCRATINGREVIEW_BASE_NAME', plugin_basename( __FILE__ ) );
defined( 'CBXMCRATINGREVIEW_ROOT_PATH' ) or define( 'CBXMCRATINGREVIEW_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXMCRATINGREVIEW_ROOT_URL' ) or define( 'CBXMCRATINGREVIEW_ROOT_URL', plugin_dir_url( __FILE__ ) );

defined( 'CBXMCRATINGREVIEW_RAND_MIN' ) or define( 'CBXMCRATINGREVIEW_RAND_MIN', 0 );
defined( 'CBXMCRATINGREVIEW_RAND_MAX' ) or define( 'CBXMCRATINGREVIEW_RAND_MAX', 999999 );
defined( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_14DAYS' ) or define( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_14DAYS', time() + 1209600 ); //Expiration of 14 days.
defined( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_7DAYS' ) or define( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_7DAYS', time() + 604800 ); //Expiration of 7 days.
//defined( 'CBXMCRATINGREVIEW_COOKIE_NAME' ) or define( 'CBXMCRATINGREVIEW_COOKIE_NAME', 'cbrating-cookie-session' );

defined( 'CBX_DEBUG' ) or define( 'CBX_DEBUG', false );
defined( 'CBXMCRATINGREVIEW_DEV_MODE' ) or define( 'CBXMCRATINGREVIEW_DEV_MODE', CBX_DEBUG );

// Include the main Cbx class.
if ( ! class_exists( 'CBXMCRatingReview', false ) ) {
	include_once CBXMCRATINGREVIEW_ROOT_PATH . 'includes/CBXMCRatingReview.php';
}

/**
 * Checking wp version
 *
 * @return bool
 */
function cbxmcratingreview_compatible_wp_version( $version = '5.3' ) {
	if ( version_compare( $GLOBALS['wp_version'], $version, '<' ) ) {
		return false;
	}

	// Add sanity checks for other version requirements here

	return true;
}//end function cbxmcratingreview_compatible_wp_version

/**
 * Checking php version
 *
 * @return bool
 */
function cbxmcratingreview_compatible_php_version( $version = '8.2' ) {
	if ( version_compare( PHP_VERSION, $version, '<=' ) ) {
		return false;
	}

	return true;
}//end function cbxmcratingreview_compatible_php_version

/**
 * The code that runs during plugin activation.
 */
function activate_cbxmcratingreview() {
	cbxmcratingreview_core();

	CBXMCRatingReviewHelper::load_orm();

	CBXMCRatingReviewHelper::activate();
}//end method activate_cbxmcratingreview

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_cbxmcratingreview() {
	CBXMCRatingReviewHelper::deactivate();
}//end method deactivate_cbxmcratingreview

register_activation_hook( __FILE__, 'activate_cbxmcratingreview' );
register_deactivation_hook( __FILE__, 'deactivate_cbxmcratingreview' );


/**
 * Initialize the plugin manually
 *
 * @return CBXMCRatingReviewPro|null
 */
function cbxmcratingreview_core() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	global $cbxmcratingreview_core;

	if ( ! isset( $cbxmcratingreview_core ) ) {
		$cbxmcratingreview_core = run_cbxmcratingreview();
	}

	return $cbxmcratingreview_core;
}//end method cbxmcratingreview_core

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_cbxmcratingreview() {
	return CBXMCRatingReview::instance();
}//end function run_cbxmcratingreview

//load the plugin
$GLOBALS['cbxmcratingreview_core'] = run_cbxmcratingreview();