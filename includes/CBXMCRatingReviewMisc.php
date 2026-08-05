<?php
namespace CBXMCRatingReview;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXMCRatingReview\Helpers\CBXMCRatingReviewHelper;


/**
 * CBX MC Rating Review Misc class
 */
class CBXMCRatingReviewMisc {
	/**
	 * Load email sending notification system
	 */
	public function load_mailer() {
		cbxmcratingreview_mailer();
	}//end method load_mailer

	/**
	 * Add module attribute to script loader
	 *
	 * @param $tag
	 * @param $handle
	 * @param $src
	 *
	 * @return mixed|string
	 */
	public function add_module_to_script( $tag, $handle, $src ) {
		$jsHandles = [
			'cbxmcratingreview_form_vue_dev',
			'cbxmcratingreview_form_vue_main',
			'cbxmcratingreview_log_vue_dev',
			'cbxmcratingreview_log_vue_main',
			'cbxmcratingreview_dashboard_vue_dev',
			'cbxmcratingreview_dashboard_vue_main',
			'cbxmcratingreview_tools_vue_dev',
			'cbxmcratingreview_tools_vue_main',
		];

		if ( in_array( $handle, $jsHandles ) ) {
			$tag = '<script type="module" id="' . $handle . '" src="' . esc_url( $src ) . '"></script>';
		}

		return $tag;
	}//end method add_module_to_script

	/**
	 * Tell bots not to index some created directories.
	 *
	 * We try to detect the default "User-agent: *" added by WordPress and add our rules to that group, because
	 * it's possible that some bots will only interpret the first group of rules if there are multiple groups with
	 * the same user agent.
	 *
	 * @param  string  $output  The contents that WordPress will output in a robots.txt file.
	 *
	 * @return string
	 */
	public function custom_robots_txt( $output ) {
		$site_url = wp_parse_url( site_url() );
		$path     = ( ! empty( $site_url['path'] ) ) ? $site_url['path'] : '';

		$lines       = preg_split( '/\r\n|\r|\n/', $output );
		$agent_index = array_search( 'User-agent: *', $lines, true );

		if ( false !== $agent_index ) {
			$above = array_slice( $lines, 0, $agent_index + 1 );
			$below = array_slice( $lines, $agent_index + 1 );
		} else {
			$above   = $lines;
			$below   = [];
			$above[] = '';
			$above[] = 'User-agent: *';
		}

		//$above[] = "Disallow: $path/wp-content/uploads/cbxmcratingreview/";
		$upload_dir   = wp_upload_dir();
		$uploads_path = trailingslashit( $upload_dir['baseurl'] ); // e.g. https://example.com/wp-content/uploads/

		// Get relative path for robots.txt (should be relative, not full URL)
		$uploads_relative_path = str_replace( home_url( '/' ), '/', $uploads_path );

		// Build your disallow path dynamically
		$above[] = 'Disallow: ' . trailingslashit( $uploads_relative_path ) . 'cbxmcratingreview/';


		$lines = array_merge( $above, $below );

		return implode( PHP_EOL, $lines );
	}//end method custom_robots_txt
}//end class CBXMCRatingReviewMisc