<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\CBXMCRatingReviewUninstall;

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


/**
 * The code that runs during plugin uninstall.
 */
function uninstall_cbxmcratingreview() {
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
	CBXMCRatingReviewUninstall::uninstall();
}//end function uninstall_cbxmcratingreview

if ( ! defined( 'CBXMCRATINGREVIEW_PLUGIN_NAME' ) ) {
	uninstall_cbxmcratingreview();
}
