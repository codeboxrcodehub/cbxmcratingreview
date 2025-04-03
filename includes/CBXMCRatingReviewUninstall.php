<?php

namespace CBX\MCRatingReview;

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use CBX\MCRatingReview\CBXMCRatingReviewSettings;

/**
 * Fired during plugin uninstall
 *
 * @link       codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 * @author     CBX Team  <info@codeboxr.com>
 */
class CBXMCRatingReviewUninstall {
	/**
	 * Uninstall plugin functionality
	 *
	 *
	 * @since    2.0.0
	 */
	public static function uninstall() {
		// For the regular site.
		if ( ! is_multisite() ) {
			self::uninstall_tasks();
		} else {
			//for multi site
			global $wpdb;


			$blog_ids         = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM %s", $wpdb->blogs ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
			$original_blog_id = get_current_blog_id();

			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );

				self::uninstall_tasks();
			}

			switch_to_blog( $original_blog_id );
		}
	}//end method uninstall

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall_tasks() {

		global $wpdb;

		$settings = new CBXMCRatingReviewSettings();

		$delete_global_config = $settings->get_field( 'delete_global_config', 'cbxmcratingreview_tools', 'no' );
		if ( $delete_global_config == 'yes' ) {
			//before hook
			do_action( 'cbxmcratingreview_plugin_uninstall_before' );

			//delete plugin global options
			$option_values = CBXMCRatingReviewHelper::getAllOptionNames();

			do_action( 'cbxmcratingreview_plugin_options_deleted_before' );

			foreach ( $option_values as $key => $option_value ) {
				$option = $option_value['option_name'];

				do_action( 'cbxmcratingreview_plugin_option_delete_before', $option );
				delete_option( $option );
				do_action( 'cbxmcratingreview_plugin_option_delete_after', $option );
			}
			//end delete options

			do_action( 'cbxmcratingreview_plugin_options_deleted_after' );
			do_action( 'cbxmcratingreview_plugin_options_deleted' );

			//delete tables
			$table_names = CBXMCRatingReviewHelper::getAllDBTablesList();

			$table_names['comment']      = $wpdb->prefix . 'cbxmcratingreviewcomment';
			$table_names['comment_feed'] = $wpdb->prefix . 'cbxmcratingreviewcomment_feedback';
			$table_names['feedback']     = $wpdb->prefix . 'cbxmcratingreview_feedback';

			if ( is_array( $table_names ) && sizeof( $table_names ) > 0 ) {
				do_action( 'cbxmcratingreview_plugin_tables_deleted_before', $table_names );

				global $wpdb;

				foreach ( $table_names as $table_name ) {
					//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$query_result = $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
				}

				//delete from migration table
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching 
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}cbxmigrations WHERE plugin IN (%s, %s)",
						'cbxmcratingreview',
						'cbxmcratingreviewpro'
					)
				);

				do_action( 'cbxmcratingreview_plugin_tables_deleted_after', $table_names );
				do_action( 'cbxmcratingreview_plugin_tables_deleted' );
			}
			//end delete tables

			//delete meta values by keys
			$meta_keys = CBXMCRatingReviewHelper::getMetaKeys();

			foreach ( $meta_keys as $key => $value ) {
				delete_post_meta_by_key( $key );
			}

			//after hook
			do_action( 'cbxmcratingreview_plugin_uninstall_after' );

			//general hook
			do_action( 'cbxmcratingreview_plugin_uninstall' );
		}
	}//end method uninstall

}