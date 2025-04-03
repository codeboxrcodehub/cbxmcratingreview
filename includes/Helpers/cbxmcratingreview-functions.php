<?php

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use enshrined\svgSanitize\Sanitizer;
use CBX\MCRatingReview\CBXMCRatingReviewSettings;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php

/**
 * This function ensures that all necessary js and css for this plugin is added properly
 *
 * enqueue css and js
 */
function cbxmcratingreview_AddJsCss() {
	return CBXMCRatingReviewHelper::AddJsCss();
}//end method cbxmcratingreview_AddJsCss

/**
 * All necessary css and js for review form
 */
function cbxmcratingreview_AddRatingFormJsCss() {
	return CBXMCRatingReviewHelper::AddRatingFormJsCss();
}//end method cbxmcratingreview_AddRatingFormJsCss

/**
 * All necessary css and js for review edit form
 *
 *
 */
function cbxmcratingreview_AddRatingEditFormJsCss() {
	return CBXMCRatingReviewHelper::AddRatingEditFormJsCss();
}//end method cbxmcratingreview_AddRatingEditFormJsCss

/**
 * is this post rated at least once
 *
 * @param int $form_id
 * @param int $post_id
 *
 * @return null|string
 */
function cbxmcratingreview_isPostRated( $form_id = 0, $post_id = 0 ) {
	return CBXMCRatingReviewHelper::isPostRated( $form_id, $post_id );
}//end method cbxmcratingreview_isPostRated

/**
 * Total reviews count of a Post
 *
 * @param int $form_id
 * @param int $post_id
 * @param string $status
 * @param string $score
 *
 * @return mixed
 */
function cbxmcratingreview_totalPostReviewsCount( $form_id = 0, $post_id = 0, $status = '', $score = '' ) {
	return CBXMCRatingReviewHelper::totalPostReviewsCount( $form_id, $post_id, $status, $score );
}//end method cbxmcratingreview_totalPostReviewsCount

/**
 * is this post rated by user at least once
 *
 * @param int $form_id
 * @param int $post_id
 * @param int $user_id
 *
 * @return boolean - true/false
 */
function cbxmcratingreview_isPostRatedByUser( $form_id = 0, $post_id = 0, $user_id = 0 ) {
	return CBXMCRatingReviewHelper::isPostRatedByUser( $form_id, $post_id, $user_id );
}//end method cbxmcratingreview_isPostRatedByUser

/**
 * Total reviews count of a Post by a User
 *
 * @param int $form_id
 * @param int $post_id
 * @param int $user_id
 * @param string $status
 *
 * @return int
 */
function cbxmcratingreview_totalPostReviewsCountByUser( $form_id = 0, $post_id = 0, $user_id = 0, $status = '' ) {
	return CBXMCRatingReviewHelper::totalPostReviewsCountByUser( $form_id, $post_id, $user_id, $status );
}//end method cbxmcratingreview_totalPostReviewsCountByUser

/**
 * User's last review date for a post by user id
 *
 * @param int $post_id
 * @param int $user_id
 *
 * @return boolean - true/false
 */
function cbxmcratingreview_lastPostReviewDateByUser( $form_id = 0, $post_id = 0, $user_id = 0 ) {
	return CBXMCRatingReviewHelper::lastPostReviewDateByUser( $form_id, $post_id, $user_id );
}//end method


/**
 * Single review data
 *
 * @param int $post_id
 *
 * @return null|string
 */
function cbxmcratingreview_singleReview( $review_id = 0 ) {
	return CBXMCRatingReviewHelper::singleReview( $review_id );
}//end method cbxmcratingreview_singleReview

/**
 * Single review data render
 *
 * @param int $post_id
 *
 * @return null|string
 */
function cbxmcratingreview_singleReviewRender( $review_id = 0 ) {
	cbxmcratingreview_AddJsCss(); //moved here from static class

	return CBXMCRatingReviewHelper::singleReviewRender( $review_id );
}//end method cbxmcratingreview_singleReviewRender


/**
 * Review lists data of a Post
 *
 * @param int $form_id
 * @param int $post_id
 * @param int $perpage
 * @param int $page
 * @param string $status
 * @param string $score
 * @param string $order_by
 * @param string $order
 *
 * @return array|null|object
 */
function cbxmcratingreview_postReviews( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $order_by = 'id', $order = 'DESC' ) {
	return CBXMCRatingReviewHelper::postReviews( $form_id, $post_id, $perpage, $page, $status, $score, $order_by, $order );
}//end method cbxmcratingreview_postReviews

/**
 * Review lists data of a Post by a User
 *
 * @param int $form_id
 * @param int $post_id
 * @param int $user_id
 * @param int $perpage
 * @param int $page
 * @param string $status
 * @param string $score
 * @param string $order_by
 * @param string $order
 *
 * @return mixed
 */
/*function cbxmcratingreview_postReviewsByUser($post_id = 0, $user_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $order_by = 'id', $order = 'DESC') {
	return CBXMCRatingReviewHelper::postReviewsByUser($post_id, $user_id, $perpage, $page, $status, $score, $order_by, $order );
}*/

function cbxmcratingreview_postReviewsFilterRender( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $score = '', $order_by = 'id', $order = 'DESC' ) {
	return CBXMCRatingReviewHelper::postReviewsFilterRender( $form_id, $post_id, $perpage, $page, 1, $score, $order_by, $order );
}//end method cbxmcratingreview_postReviewsFilterRender

/**
 * Render Review lists data of a Post
 *
 * @param int $form_id
 * @param int $post_id
 * @param int $perpage
 * @param int $page
 * @param string $score
 * @param string $order_by
 * @param string $order
 * @param bool $load_more
 * @param bool $show_filter
 *
 * @return string
 */
function cbxmcratingreview_postReviewsRender( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $score = '', $order_by = 'id', $order = 'DESC', $load_more = false, $show_filter = true ) {
	cbxmcratingreview_AddJsCss(); //moved here from static class

	return CBXMCRatingReviewHelper::postReviewsRender( $form_id, $post_id, $perpage, $page, 1, $score, $order_by, $order, $load_more, $show_filter );
}//end method cbxmcratingreview_postReviewsRender


/**
 * Reviews list data
 *
 * @param int $form_id
 * @param int $perpage
 * @param int $page
 * @param string $status
 * @param string $order_by
 * @param string $order
 * @param string $score
 *
 * @return mixed
 */
function cbxmcratingreview_Reviews( $form_id = '', $perpage = 10, $page = 1, $status = '', $order_by = 'id', $order = 'DESC', $score = '' ) {
	return CBXMCRatingReviewHelper::Reviews( $form_id, $perpage, $page, $status, $order_by, $order, $score );
}//end method cbxmcratingreview_Reviews

/**
 * Total reviews count
 *
 * @param int/string $form_id
 * @param string $status
 * @param string $filter_score
 *
 * @return mixed
 */
function cbxmcratingreview_totalReviewsCount( $form_id = '', $status = '', $filter_score = '' ) {
	return CBXMCRatingReviewHelper::totalReviewsCount( $form_id, $status, $filter_score );
}//end method cbxmcratingreview_totalReviewsCount

/**
 * Total reviews count by post type, status and filter
 *
 * @param int/string $form_id
 * @param string $post_type
 * @param string $status
 * @param string $filter_score
 *
 * @return mixed
 */
function cbxmcratingreview_totalReviewsCountPostType( $form_id = '', $post_type = 'post', $status = '', $filter_score = '' ) {
	return CBXMCRatingReviewHelper::totalReviewsCountPostType( $form_id, $post_type, $status, $filter_score );
}//end method cbxmcratingreview_totalReviewsCountPostType

/**
 * Total reviews count by User
 *
 * @param int/string $form_id
 * @param int $user_id
 * @param string $status
 * @param string $filter_score
 *
 * @return mixed
 */
function cbxmcratingreview_totalReviewsCountByUser( $form_id = '', $user_id = 0, $status = '', $filter_score = '' ) {
	return CBXMCRatingReviewHelper::totalReviewsCountByUser( $form_id, $user_id, $status, $filter_score );
}//end method cbxmcratingreview_totalReviewsCountByUser


/**
 * Reviews list data by a User
 *
 * @param int/string    $form_id
 * @param int $user_id
 * @param int $perpage
 * @param int $page
 * @param string $status
 * @param string $order_by
 * @param string $order
 * @param string $filter_score
 *
 * @return array|null|object
 */
function cbxmcratingreview_ReviewsByUser( $form_id = '', $user_id = 0, $perpage = 10, $page = 1, $status = '', $order_by = 'id', $order = 'DESC', $filter_score = '' ) {
	return CBXMCRatingReviewHelper::ReviewsByUser( $form_id, $user_id, $perpage, $page, $status, $order_by, $order, $filter_score );
}//end method cbxmcratingreview_ReviewsByUser


/**
 * Average rating information of a post by post id
 *
 * @param int $form_id
 * @param int $post_id
 *
 * @return null|string
 */
function cbxmcratingreview_postAvgRatingInfo( $form_id = 0, $post_id = 0 ) {
	return CBXMCRatingReviewHelper::postAvgRatingInfo( $form_id, $post_id );
}//end method cbxmcratingreview_postAvgRatingInfo

/**
 * Render single post avg rating for a form
 *
 * @param int $form_id
 * @param int $post_id
 * @param boolean $show_chart
 * @param boolean $show_score
 */
function cbxmcratingreview_postAvgRatingRender( $form_id = 0, $post_id = 0, $show_star = true, $show_score = true, $show_chart = false ) {
	return CBXMCRatingReviewHelper::postAvgRatingRender( $form_id, $post_id, $show_star, $show_score, $show_chart );
}//end method cbxmcratingreview_postAvgRatingRender

/**
 * Render single post avg rating for a form
 *
 * @param int $form_id
 * @param int $post_id
 * @param boolean $show_chart
 * @param boolean $show_score
 */
function cbxmcratingreview_postAvgDetailsRatingRender( $form_id = 0, $post_id = 0, $show_star = true, $show_score = true, $show_short = true, $show_chart = true ) {
	return CBXMCRatingReviewHelper::postAvgDetailsRatingRender( $form_id, $post_id, $show_star, $show_score, $show_short, $show_chart );
}//end method cbxmcratingreview_postAvgDetailsRatingRender

/**
 * Single avg rating info by avg id
 *
 * @param int $avg_id
 *
 * @return null|string
 */
function cbxmcratingreview_singleAvgRatingInfo( $avg_id = 0 ) {
	return CBXMCRatingReviewHelper::singleAvgRatingInfo( $avg_id );
}//end method cbxmcratingreview_singleAvgRatingInfo

/**
 * Get most rated posts
 *
 * @param int $form_id
 * @param int $limit
 * @param string $order_by
 * @param string $order
 * @param string $type
 *
 * @return array|null|object
 */
function cbxmcratingreview_most_rated_posts( $form_id = 0, $limit = 10, $order_by = 'avg_rating', $order = 'DESC', $type = 'post' ) {
	return CBXMCRatingReviewHelper::most_rated_posts( $form_id, $limit, $order_by, $order, $type );
}//end method cbxmcratingreview_mostRatedPosts

/**
 * latest ratings of all post
 *
 * @param int $form_id
 * @param int $limit
 * @param string $order_by
 * @param string $order
 * @param string $type
 * @param int $user_id
 *
 * @return array|null|object
 */
function cbxmcratingreview_lastest_ratings( $form_id = 0, $limit = 10, $order_by = 'id', $order = 'DESC', $type = 'post', $user_id = 0 ) {
	return CBXMCRatingReviewHelper::lastest_ratings( $form_id, $limit, $order_by, $order, $type, $user_id );
}//end method cbxmcratingreview_latestRatings

/**
 * latest ratings of author post
 */
/*function cbxmcratingreview_authorpostlatestRatings( $limit = 10, $user_id = 0 ) {
	return CBXMCRatingReviewHelper::authorpostlatestRatings( $limit, $user_id );
}//end method cbxmcratingreview_authorpostlatestRatings*/

/**
 * Render rating review form
 *
 * @param int $form_id
 * @param int $post_id
 *
 * @return string
 */
function cbxmcratingreview_reviewformRender( $form_id = 0, $post_id = 0 ) {
	return CBXMCRatingReviewHelper::reviewformRender( $form_id, $post_id );
}//end method cbxmcratingreview_reviewformRender

/**
 * paginate_links_as_bootstrap()
 * JPS 20170330
 * Wraps paginate_links data in Twitter bootstrap pagination component
 *
 * @param array $args {
 *                         Optional. {@see 'paginate_links'} for native argument list.
 *
 * @type string $nav_class classes for <nav> element. Default empty.
 * @type string $ul_class additional classes for <ul.pagination> element. Default empty.
 * @type string $li_class additional classes for <li> elements.
 * }
 * @return array|string|void String of page links or array of page links.
 */
function cbxmcratingreview_paginate_links_as_bootstrap( $args = '' ) {
	$args['type'] = 'array';
	$defaults     = [
		'nav_class' => '',
		'ul_class'  => '',
		'li_class'  => ''
	];
	$args         = wp_parse_args( $args, $defaults );
	$page_links   = paginate_links( $args );

	if ( $page_links ) {
		$r         = '';
		$nav_class = empty( $args['nav_class'] ) ? '' : 'class="' . $args['nav_class'] . '"';
		$ul_class  = empty( $args['ul_class'] ) ? '' : ' ' . $args['ul_class'];

		//$r .= '<nav '. $nav_class .' aria-label="navigation">' . "\n\t";
		$r .= '<div ' . $nav_class . ' aria-label="navigation">' . "\n\t";

		$r .= '<ul class="pagination' . $ul_class . '">' . "\n";
		foreach ( $page_links as $link ) {
			$li_classes = explode( " ", $args['li_class'] );
			strpos( $link, 'current' ) !== false ? array_push( $li_classes, 'active' ) : ( strpos( $link, 'dots' ) !== false ? array_push( $li_classes, 'disabled' ) : '' );
			$class = empty( $li_classes ) ? '' : 'class="' . join( " ", $li_classes ) . '"';
			$r     .= "\t\t" . '<li ' . $class . '>' . $link . '</li>' . "\n";
		}
		$r .= "\t</ul>";
		$r .= "\n</div>";

		return '<div class="clearfix"></div><nav class="blog-page--pagination cbxmcratingreview_paginate_links">' . $r . '</nav><div class="clearfix"></div>';
	}
}//end function cbxmcratingreview_paginate_links_as_bootstrap


/**
 * Review permalink
 *
 * @param $review_id
 *
 * @return string
 */
function cbxmcratingreview_review_permalink( $review_id ) {
	$settings = new CBXMCRatingReviewSettings();;

	//global $wp_rewrite;
	$review_id = intval( $review_id );


	if ( $review_id == 0 ) {
		return '#';
	}

	$single_review_page_id = intval( $settings->get_field( 'single_review_view_id', 'cbxmcratingreview_tools', 0 ) );


	if ( $single_review_page_id > 0 ) {

		$single_review_page_link = get_permalink( $single_review_page_id );

		return add_query_arg( [ 'review_id' => $review_id ], $single_review_page_link ) . '#cbxmcratingreview_review_list_item_' . $review_id;
	}

	return '#';
}//end method cbxmcratingreview_review_permalink

/**
 * Reviews toolbar render
 *
 * @param $post_review
 *
 * @return false|string
 */
function cbxmcratingreview_reviewToolbarRender( $post_review ) {
	return CBXMCRatingReviewHelper::reviewToolbarRender( $post_review );
}//end method cbxmcratingreview_reviewToolbarRender

/**
 * render single review delete button
 *
 * @param array $post_review
 *
 * @return string
 */
function cbxmcratingreview_reviewDeleteButtonRender( $post_review = [] ) {
	return CBXMCRatingReviewHelper::reviewDeleteButtonRender( $post_review );
}//end method cbxmcratingreview_reviewDeleteButtonRender


if ( ! function_exists( 'getMonthlyReviewCounts' ) ) {
	function getMonthlyReviewCounts( $year = null ) {
		return CBXMCRatingReviewHelper::getMonthlyReviewCounts( $year );
	}//end method cbxmcratingreview_signature_get_sortable_keys
}

if ( ! function_exists( 'getWeeklyReviewCounts' ) ) {
	function getWeeklyReviewCounts( $review_id = 0 ) {
		return CBXMCRatingReviewHelper::getWeeklyReviewCounts( $review_id );
	}//end method cbxmcratingreview_signature_get_sortable_keys
}


if ( ! function_exists( 'cbxmcratingreview_esc_svg' ) ) {
	/**
	 * SVG sanitizer
	 *
	 * @param string $svg_content The content of the SVG file
	 *
	 * @return string|false The SVG content if found, or false on failure.
	 * @since 1.0.0
	 */
	function cbxmcratingreview_esc_svg( $svg_content = '' ) {
		// Create a new sanitizer instance
		$sanitizer = new Sanitizer();

		return $sanitizer->sanitize( $svg_content );
	}// end method cbxmcratingreview_esc_svg
}


if ( ! function_exists( 'cbxmcratingreview_load_svg' ) ) {
	/**
	 * Load an SVG file from a directory.
	 *
	 * @param string $svg_name The name of the SVG file (without the .svg extension).
	 * @param string $directory The directory where the SVG files are stored.
	 *
	 * @return string|false The SVG content if found, or false on failure.
	 * @since 1.0.0
	 */
	function cbxmcratingreview_load_svg( $svg_name = '', $folder = '' ) {
		//note: code partially generated using chatgpt
		if ( $svg_name == '' ) {
			return '';
		}

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$credentials = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, null );
		if ( ! WP_Filesystem( $credentials ) ) {
			return; // Error handling here
		}

		global $wp_filesystem;

		$directory = cbxmcratingreview_icon_path();

		// Sanitize the file name to prevent directory traversal attacks.
		$svg_name = sanitize_file_name( $svg_name );
		if ( $folder != '' ) {
			$folder = trailingslashit( $folder );
		}

		// Construct the full file path.
		$file_path = $directory . $folder . $svg_name . '.svg';

		$file_path = apply_filters( 'cbxmcratingreview_svg_file_path', $file_path, $svg_name );

		// Check if the file exists.
		if ( $wp_filesystem->exists( $file_path ) && is_readable( $file_path ) ) {
			// Get the SVG file content.
			return $wp_filesystem->get_contents( $file_path );
		} else {
			// Return false if the file does not exist or is not readable.
			return '';
		}
	}//end method cbxmcratingreview_load_svg
}

if ( ! function_exists( 'cbxmcratingreview_icon_path' ) ) {
	/**
	 * Form icon path
	 *
	 * @return mixed|null
	 * @since 1.0.0
	 */
	function cbxmcratingreview_icon_path() {
		$directory = trailingslashit( CBXMCRATINGREVIEW_ROOT_PATH ) . 'assets/icons/';

		return apply_filters( 'cbxmcratingreview_icon_path', $directory );
	}//end method cbxmcratingreview_icon_path
}


if ( ! function_exists( 'cbxmcratingreview_all_caps' ) ) {
	/**
	 * All form caps
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function cbxmcratingreview_all_caps() {
		$all_caps = array_merge( cbxmcratingreview_log_caps(), cbxmcratingreview_form_caps() );

		return apply_filters( 'cbxmcratingreview_all_caps', $all_caps );
	}//end function cbxmcratingreview_all_caps
}

if ( ! function_exists( 'cbxmcratingreview_log_caps' ) ) {
	/**
	 * cbxmcratingreview component capabilities for log manager
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function cbxmcratingreview_log_caps() {
		//format: plugin_component_verb
		$caps = [
			'cbxmcratingreview_dashboard_manage',
			'cbxmcratingreview_settings_manage',
			'cbxmcratingreview_log_manage',
			'cbxmcratingreview_log_view',
			'cbxmcratingreview_log_edit',
			'cbxmcratingreview_log_delete',
		];

		return apply_filters( 'cbxmcratingreview_log_caps', $caps );
	}//end function cbxmcratingreview_log_caps
}

if ( ! function_exists( 'cbxmcratingreview_form_caps' ) ) {
	/**
	 * cbxmcratingreview component capabilities for form manager
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function cbxmcratingreview_form_caps() {
		//format: plugin_component_verb
		$caps = [
			'cbxmcratingreview_form_manage',
			'cbxmcratingreview_form_view',
			'cbxmcratingreview_form_edit',
			'cbxmcratingreview_form_delete',
		];

		return apply_filters( 'cbxmcratingreview_form_caps', $caps );
	}//end function cbxmcratingreview_form_caps
}

/**
 * Init the cbxmcratingreview_mailer
 *
 */
function cbxmcratingreview_mailer() {
	if ( ! class_exists( 'CBXMCRatingReviewEmails' ) ) {
		include_once __DIR__ . '/../CBXMCRatingReviewEmails.php';
	}

	return CBXMCRatingReviewEmails::instance();
}//end method cbxmcratingreview_mailer

if ( ! function_exists( 'cbxmcratingreview_check_pro_plugin_active' ) ) {
	function cbxmcratingreview_check_pro_plugin_active() {
		if ( defined( 'CBXMCRATINGREVIEWPRO_PLUGIN_NAME' ) && CBXMCRATINGREVIEWPRO_PLUGIN_NAME ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'cbxmcratingreview_deprecated_function' ) ) {
	/**
	 * Wrapper for deprecated functions so we can apply some extra logic.
	 *
	 * @param string $function
	 * @param string $version
	 * @param string $replacement
	 *
	 * @since  2.0.5
	 *
	 */
	function cbxmcratingreview_deprecated_function( $function, $version, $replacement = null ) {
		if ( defined( 'DOING_AJAX' ) ) {
			do_action( 'deprecated_function_run', $function, $replacement, $version );
			$log_string = "The {$function} function is deprecated since version {$version}."; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$log_string .= $replacement ? " Replace with {$replacement}." : '';               // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
				error_log( $log_string );//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
		} else {
			_deprecated_function( $function, $version, $replacement ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}//end function cbxmcratingreview_deprecated_function
}

if ( ! function_exists( 'cbxmcratingreview_is_rest_api_request' ) ) {
	/**
	 * Check if doing rest request
	 *
	 * @return bool
	 */
	function cbxmcratingreview_is_rest_api_request() {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}

		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix = trailingslashit( rest_get_url_prefix() );

		return ( false !== strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), $rest_prefix ) );
	}//end function cbxmcratingreview_is_rest_api_request
}

if ( ! function_exists( 'cbxmcratingreview_doing_it_wrong' ) ) {
	/**
	 * Wrapper for _doing_it_wrong().
	 *
	 * @param string $function Function used.
	 * @param string $message Message to log.
	 * @param string $version Version the message was added in.
	 *
	 * @since  1.0.0
	 */
	function cbxmcratingreview_doing_it_wrong( $function, $message, $version ) {
		// @codingStandardsIgnoreStart
		$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

		if ( wp_doing_ajax() || cbxmcratingreview_is_rest_api_request() ) {
			do_action( 'doing_it_wrong_run', $function, $message, $version );
			error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
		} else {
			_doing_it_wrong( $function, $message, $version );
		}
		// @codingStandardsIgnoreEnd
	}//end function cbxmcratingreview_doing_it_wrong
}

if ( ! function_exists( 'cbxmcratingreview_check_version_and_deactivate_plugin' ) ) {
	/**
	 * Check any plugin active, check version, if less than x then deactivate
	 *
	 * @param string $plugin_slug plugin slug
	 * @param string $required_version required plugin version
	 * @param string $transient transient name
	 *
	 * @return bool|void
	 * @since 2.0.0
	 */
	function cbxmcratingreview_check_version_and_deactivate_plugin( $plugin_slug = '', $required_version = '', $transient = '' ) {
		if ( $plugin_slug == '' ) {
			return;
		}

		if ( $required_version == '' ) {
			return;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		// Check if the plugin is active
		if ( is_plugin_active( $plugin_slug ) ) {
			// Get the plugin data
			$plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_slug );
			$plugin_version = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';
			if ( $plugin_version == '' || is_null( $plugin_version ) ) {
				return;
			}

			// Compare the plugin version with the required version
			if ( version_compare( $plugin_version, $required_version, '<' ) ) {
				// Deactivate the plugin
				deactivate_plugins( $plugin_slug );
				if ( $transient != '' ) {
					set_transient( $transient, 1 );
				}
			}
		}

		//return false;
	}//end method cbxmcratingreview_check_version_and_deactivate_plugin
}

if ( ! function_exists( 'cbxmcratingreview_check_and_deactivate_plugin' ) ) {
	/**
	 * Check if any plugin activated and then deactivate
	 *
	 * @param string $plugin_slug plugin slug
	 * @param string $transient transient name
	 *
	 * @return bool|void
	 * @since 2.0.0
	 */
	function cbxmcratingreview_check_and_deactivate_plugin( $plugin_slug = '', $transient = '' ) {
		if ( $plugin_slug == '' ) {
			return;
		}


		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		// Check if the plugin is active
		if ( is_plugin_active( $plugin_slug ) ) {
			// Get the plugin data
			$plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_slug );
			$plugin_version = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';
			if ( $plugin_version == '' || is_null( $plugin_version ) ) {
				return;
			}


			// Deactivate the plugin
			deactivate_plugins( $plugin_slug );
			if ( $transient != '' ) {
				set_transient( $transient, 1 );
			}

		}

		//return false;
	}//end method cbxmcratingreview_check_and_deactivate_plugin
}

if ( ! function_exists( 'cbxmcratingreview_login_url_with_redirect' ) ) {
	function cbxmcratingreview_login_url_with_redirect() {
		//$login_url          = wp_login_url();
		//$redirect_url       = '';

		if ( is_singular() ) {
			$login_url = wp_login_url( get_permalink() );
			//$redirect_url = get_permalink();
		} else {
			global $wp;
			$login_url = wp_login_url( home_url( add_query_arg( [], $wp->request ) ) );
			//$redirect_url = home_url( add_query_arg( [], $wp->request ) );
		}

		return $login_url;
	}//end function cbxmcratingreview_login_url_with_redirect
}

if ( ! function_exists( 'cbxmcratingreview_get_order_keys' ) ) {
	function cbxmcratingreview_get_order_keys() {
		return CBXMCRatingReviewHelper::sort_orders();
	}//end function cbxmcratingreview_get_order_keys
}