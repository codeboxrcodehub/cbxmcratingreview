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
 * Version:           2.0.5
 * Author:            Codeboxr
 * Author URI:        https://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxmcratingreview
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXMCRatingReview\Helpers\CBXMCRatingReviewHelper;


defined( 'CBXMCRATINGREVIEW_PLUGIN_NAME' ) or define( 'CBXMCRATINGREVIEW_PLUGIN_NAME', 'cbxmcratingreview' );
defined( 'CBXMCRATINGREVIEW_PLUGIN_VERSION' ) or define( 'CBXMCRATINGREVIEW_PLUGIN_VERSION', '2.0.5' );
defined( 'CBXMCRATINGREVIEW_BASE_NAME' ) or define( 'CBXMCRATINGREVIEW_BASE_NAME', plugin_basename( __FILE__ ) );
defined( 'CBXMCRATINGREVIEW_ROOT_PATH' ) or define( 'CBXMCRATINGREVIEW_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXMCRATINGREVIEW_ROOT_URL' ) or define( 'CBXMCRATINGREVIEW_ROOT_URL', plugin_dir_url( __FILE__ ) );

defined( 'CBXMCRATINGREVIEW_RAND_MIN' ) or define( 'CBXMCRATINGREVIEW_RAND_MIN', 0 );
defined( 'CBXMCRATINGREVIEW_RAND_MAX' ) or define( 'CBXMCRATINGREVIEW_RAND_MAX', 999999 );
defined( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_14DAYS' ) or define( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_14DAYS', time() + 1209600 ); //Expiration of 14 days.
defined( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_7DAYS' ) or define( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_7DAYS', time() + 604800 ); //Expiration of 7 days.


defined( 'CBXMCRATINGREVIEW_PHP_MIN_VERSION' ) or define( 'CBXMCRATINGREVIEW_PHP_MIN_VERSION', '8.2' );
defined( 'CBXMCRATINGREVIEW_WP_MIN_VERSION' ) or define( 'CBXMCRATINGREVIEW_WP_MIN_VERSION', '6.9' );

defined( 'CBXMCRATINGREVIEW_DEV_MODE' ) or define( 'CBXMCRATINGREVIEW_DEV_MODE', false );
defined( 'CBXMCRATINGREVIEW_PRO_VERSION' ) or define( 'CBXMCRATINGREVIEW_PRO_VERSION', '2.0.5' );

// Include the main Cbx class.
if ( ! class_exists( 'CBXMCRatingReview', false ) ) {
	include_once CBXMCRATINGREVIEW_ROOT_PATH . 'includes/CBXMCRatingReview.php';
}

/**
 * Checking wp version
 *
 * @return bool
 */
function cbxmcratingreview_compatible_wp_version( $version = '' ) {
	if ( $version == '' ) {
		$version = CBXMCRATINGREVIEW_WP_MIN_VERSION;
	}

	if ( version_compare( $GLOBALS['wp_version'], $version, '<' ) ) {
		return false;
	}

	return true;
}//end function cbxmcratingreview_compatible_wp_version

/**
 * Checking php version
 *
 * @return bool
 */
function cbxmcratingreview_compatible_php_version( $version = '' ) {
	if ( $version == '' ) {
		$version = CBXMCRATINGREVIEW_PHP_MIN_VERSION;
	}


	if ( version_compare( PHP_VERSION, $version, '<' ) ) {
		return false;
	}

	return true;
}//end function cbxmcratingreview_compatible_php_version

/**
 * The code that runs during plugin activation.
 */
function cbxmcratingreview_activate() {
	//phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DevelopmentFunctions.error_log_print_r
	try {
		$wp_version  = CBXMCRATINGREVIEW_WP_MIN_VERSION;
		$php_version = CBXMCRATINGREVIEW_PHP_MIN_VERSION;
		$activate_ok = true;

		if ( ! cbxmcratingreview_compatible_wp_version() ) {
			$activate_ok = false;
			deactivate_plugins( plugin_basename( __FILE__ ) );

			/* translators: WordPress version */
			wp_die( sprintf( esc_html__( 'CBX Multi Criteria Rating & Review plugin requires WordPress %s or higher!', 'cbxmcratingreview' ), $wp_version ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( ! cbxmcratingreview_compatible_php_version() ) {
			$activate_ok = false;
			deactivate_plugins( plugin_basename( __FILE__ ) );

			/* translators: PHP version */
			wp_die( sprintf( esc_html__( 'CBX Multi Criteria Rating & Review plugin requires PHP %s or higher!', 'cbxmcratingreview' ), $php_version ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( $activate_ok ) {
			cbxmcratingreview_core();
			CBXMCRatingReviewHelper::load_orm();
			CBXMCRatingReviewHelper::activate();
		}
	} catch ( Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			error_log( print_r( [
				'message' => $e->getMessage(),
				'file'    => $e->getFile(),
				'line'    => $e->getLine(),
				'trace'   => $e->getTraceAsString(),
			], true ) );
		}


		deactivate_plugins( plugin_basename( __FILE__ ) );

		wp_die(
			sprintf(
				'Activation error: %s',
				esc_html( $e->getMessage() )
			)
		);
	}
	//phpcs:enable
}//end method cbxmcratingreview_activate

/**
 * The code that runs during plugin deactivation.
 */
function cbxmcratingreview_deactivate() {
	CBXMCRatingReviewHelper::deactivate();
}//end method cbxmcratingreview_deactivate

register_activation_hook( __FILE__, 'cbxmcratingreview_activate' );
register_deactivation_hook( __FILE__, 'cbxmcratingreview_deactivate' );


/**
 * Initialize the plugin manually
 *
 * @return CBXMCRatingReview|null
 */
function cbxmcratingreview_core() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	global $cbxmcratingreview_core;

	if ( ! isset( $cbxmcratingreview_core ) ) {
		$cbxmcratingreview_core = cbxmcratingreview_run();
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
function cbxmcratingreview_run() {
	//cbxmcratingreview_check_version_and_deactivate_plugin( 'cbxmcratingreviewpro/cbxmcratingreviewpro.php', '2.0.5', 'cbxmcratingreview_proaddon_deactivated' );
	return CBXMCRatingReview::instance();
}//end function cbxmcratingreview_run

//load the plugin
$GLOBALS['cbxmcratingreview_core'] = cbxmcratingreview_run();