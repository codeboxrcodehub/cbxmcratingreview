<?php
namespace CBX\MCRatingReview;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;


/**
 * CBX MC Rating Review Misc class
 */
class CBXMCRatingReviewMisc {
	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return  array
	 * @since 2.0.0
	 */
	public static function plugin_action_links( $links ) {
		$action_links = [
			'settings' => '<a style="color: #6648fe !important; font-weight: bold;" href="' . admin_url( 'admin.php?page=cbxmcratingreview-settings' ) . '" aria-label="' . esc_attr__( 'View settings',
					'cbxmcratingreview' ) . '">' . esc_html__( 'Settings', 'cbxmcratingreview' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}//load_plugin_textdomain

	/**
	 * Plugin textdomain
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'cbxmcratingreview', false, CBXMCRATINGREVIEW_ROOT_PATH . 'languages/' );
	}//end method load_mailer

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
	}//end method custom_robots_txt

	/**
	 * If we need to do something in upgrader process is completed
	 *
	 */
	public function plugin_upgrader_process_complete() {
		$saved_version = get_option( 'cbxmcratingreview_version' );

		if ( $saved_version === false || version_compare( $saved_version, CBXMCRATINGREVIEW_PLUGIN_VERSION, '<' ) ) {
			//load orm
			CBXMCRatingReviewHelper::load_orm();

			// Run the upgrade routine
			CBXMCRatingReviewHelper::migration_and_defaults();

			add_action( 'init', [ $this, 'plugin_upgrader_process_complete_partial' ] );

			//set upgrade notice in transient
			set_transient( 'cbxmcratingreview_upgraded_notice', 1 );

			// Update the saved version
			update_option( 'cbxmcratingreview_version', CBXMCRATINGREVIEW_PLUGIN_VERSION );

			//disable comment and mycred
		}
	}//end plugin_upgrader_process_complete

	/**
	 * Run partial migration
	 *
	 * @return void
	 */
	public function plugin_upgrader_process_complete_partial() {
		CBXMCRatingReviewHelper::create_pages();
	}//end method plugin_upgrader_process_complete_partial


	/**
	 * Show a notice to anyone who has just installed the plugin for the first time
	 * This notice shouldn't display to anyone who has just updated this plugin
	 */
	public function plugin_activate_upgrade_notices() {
		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxmcratingreview_activated_notice' ) ) {
			echo '<div style="border-left-color: #005ae0;" class="notice notice-success is-dismissible">';
			/* translators: %s: bookmark core plugin version */
			echo '<p>' . sprintf( wp_kses( __( 'Thanks for installing/deactivating <strong>CBX Multi Criteria Rating & Review</strong> V%s - Codeboxr Team', 'cbxmcratingreview' ), [ 'strong' => [] ] ), esc_attr( CBXMCRATINGREVIEW_PLUGIN_VERSION ) ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			/* translators: 1. Plugin setting url 2. Documentation link */
			echo '<p>' . sprintf( wp_kses( __( 'Check <a style="color:#005ae0 !important; font-weight: bold;" href="%1$s">Plugin Setting</a> | <a style="color:#005ae0 !important; font-weight: bold;" href="%2$s" target="_blank">Documentation</a>', 'cbxmcratingreview' ), [ 'a' => [ 'href'   => [],
			                                                                                                                                                                                                                                                                             'style'  => [],
			                                                                                                                                                                                                                                                                             'target' => []
				]
				] ), esc_url( admin_url( 'admin.php?page=cbxmcratingreview-settings' ) ),
					'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/' ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';

			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxmcratingreview_activated_notice' );

			//$this->pro_addon_compatibility_campaign();
		}

		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxmcratingreview_upgraded_notice' ) ) {
			echo '<div style="border-left-color: #005ae0;" class="notice notice-success is-dismissible">';
			/* translators: %s: bookmark core plugin version */
			echo '<p>' . sprintf( wp_kses( __( 'Thanks for upgrading <strong>CBX Multi Criteria Rating & Review</strong> V%s , enjoy the new features and bug fixes - Codeboxr Team', 'cbxmcratingreview' ), [ 'strong' => [] ] ), esc_attr( CBXMCRATINGREVIEW_PLUGIN_VERSION ) ) . '</p>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			/* translators: 1. Plugin setting url 2. Documentation link */
			echo '<p>' . sprintf( wp_kses( __( 'Check <a style="color:#005ae0 !important; font-weight: bold;" href="%1$s">Plugin Setting</a> | <a style="color:#005ae0 !important; font-weight: bold;" href="%2$s" target="_blank">Documentation</a>', 'cbxmcratingreview' ), [ 'a' => [ 'href'   => [],
			                                                                                                                                                                                                                                                                             'style'  => [],
			                                                                                                                                                                                                                                                                             'target' => []
				]
				] ), esc_url( admin_url( 'admin.php?page=cbxmcratingreview-settings' ) ), 'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/' ) . '</p>';
			echo '</div>';

			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxmcratingreview_upgraded_notice' );

			//$this->pro_addon_compatibility_campaign();
		}
	}//end plugin_activate_upgrade_notices

	/**
	 * Filters the array of row meta for each/specific plugin in the Plugins list table.
	 * Appends additional links below each/specific plugin on the plugins page.
	 *
	 * @access  public
	 *
	 * @param array $links_array An array of the plugin's metadata
	 * @param string $plugin_file_name Path to the plugin file
	 * @param array $plugin_data An array of plugin data
	 * @param string $status Status of the plugin
	 *
	 * @return  array       $links_array
	 * @since 2.0.0
	 */
	public function plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
		if ( strpos( $plugin_file_name, CBXMCRATINGREVIEW_BASE_NAME ) !== false ) {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$links_array[] = '<a target="_blank" style="color:#6044ea !important; font-weight: bold;" href="https://wordpress.org/support/plugin/cbxmcratingreview/" aria-label="' . esc_attr__( 'Free Support',
					'cbxmcratingreview' ) . '">' . esc_html__( 'Free Support', 'cbxmcratingreview' ) . '</a>';

			$links_array[] = '<a target="_blank" style="color:#6044ea !important; font-weight: bold;" href="https://wordpress.org/plugins/cbxmcratingreview/#reviews" aria-label="' . esc_attr__( 'Reviews',
					'cbxmcratingreview' ) . '">' . esc_html__( 'Reviews', 'cbxmcratingreview' ) . '</a>';


			$links_array[] = '<a target="_blank" style="color:#6044ea !important; font-weight: bold;" href="https://codeboxr.com/doc/cbxmcratingreview-doc/" aria-label="' . esc_attr__( 'Documentation',
					'cbxmcratingreview' ) . '">' . esc_html__( 'Documentation', 'cbxmcratingreview' ) . '</a>';

			if ( defined( 'CBXMCRATINGREVIEWPRO_PLUGIN_NAME' ) && in_array( 'cbxmcratingreviewpro/cbxmcratingreviewpro.php', apply_filters( 'active_plugins',
					get_option( 'active_plugins' ) ) ) ) {
				//pro addon active
			} else {
				$links_array[] = '<a target="_blank" style="color:#6044ea !important; font-weight: bold;" href="https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/" aria-label="' . esc_attr__( 'Try Pro Addon',
						'cbxmcratingreview' ) . '">' . esc_html__( 'Try Pro Addon', 'cbxmcratingreview' ) . '</a>';
			}

		}

		return $links_array;
	}//end plugin_action_links

	/**
	 * Tell bots not to index some created directories.
	 *
	 * We try to detect the default "User-agent: *" added by WordPress and add our rules to that group, because
	 * it's possible that some bots will only interpret the first group of rules if there are multiple groups with
	 * the same user agent.
	 *
	 * @param string $output The contents that WordPress will output in a robots.txt file.
	 *
	 * @return string
	 */
	private function custom_robots_txt( $output ) {
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

		$above[] = "Disallow: $path/wp-content/uploads/cbxmcratingreview/";

		$lines = array_merge( $above, $below );

		return implode( PHP_EOL, $lines );
	}//end plugin_row_meta
}//end class CBXMCRatingReviewMisc
