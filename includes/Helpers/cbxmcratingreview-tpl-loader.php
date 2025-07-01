<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get the template path.
 *
 * @return string
 */
function cbxmcratingreview_template_path() {
	return apply_filters( 'cbxmcratingreview_template_path', 'cbxmcratingreview/' );
}//end method cbxmcratingreview_template_path

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @return string
 */
function cbxmcratingreview_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = cbxmcratingreview_template_path();
	}

	if ( ! $default_path ) {
		$default_path = CBXMCRATINGREVIEW_ROOT_PATH . 'templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		[
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		]
	);

	// Get default template/.
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'cbxmcratingreview_locate_template', $template, $template_name, $template_path );
}//end function cbxmcratingreview_locate_template

/**
 * Like cbxmcratingreview_get_template, but returns the HTML instead of outputting.
 *
 * @param string $template_name Template name.
 * @param array $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @return string
 * @since 2.5.0
 *
 * @see   cbxmcratingreview_get_template
 */
function cbxmcratingreview_get_template_html( $template_name, $args = [], $template_path = '', $default_path = '' ) {
	ob_start();
	cbxmcratingreview_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}//end function cbxmcratingreview_get_template_html

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 */
function cbxmcratingreview_get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}

	$located = cbxmcratingreview_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		/* translators: %s template */
		cbxmcratingreview_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'cbxmcratingreview' ), '<code>' . $located . '</code>' ), '2.0.0' );

		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'cbxmcratingreview_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'cbxmcratingreview_before_template_part', $template_name, $template_path, $located, $args );

	include $located;

	do_action( 'cbxmcratingreview_after_template_part', $template_name, $template_path, $located, $args );
}//end function cbxmcratingreview_get_template