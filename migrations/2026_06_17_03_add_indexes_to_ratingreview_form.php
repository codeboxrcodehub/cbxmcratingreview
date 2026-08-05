<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXMCRatingReviewScoped\Illuminate\Database\Capsule\Manager as Capsule;

if ( ! class_exists( 'CBXMcRatingReviewFormAddIndexes' ) ) {
	/**
	 * Class CBXMcRatingReviewFormAddIndexes
	 * @since 2.0.0
	 */
	class CBXMcRatingReviewFormAddIndexes {

		/**
		 * Run migration
		 */
		public static function up() {
			global $wpdb;
			$table_name = 'cbxmcratingreview_form';
			$prefixed_table = $wpdb->prefix . $table_name;

			//phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {
					Capsule::schema()->table( $table_name, function ( $table ) use ( $wpdb, $prefixed_table ) {
						// Add index for status if not exists
						/*$index_status = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxmcratingreview_form_status_index' ) );
						if ( ! $index_status ) {
							$table->index( 'status', 'cbxmcratingreview_form_status_index' );
						}*/
						$escaped_table = esc_sql( $prefixed_table );

						$exists = $wpdb->get_var( $wpdb->prepare(
							"SHOW INDEX FROM " . $escaped_table . " WHERE Key_name = %s",
							'cbxmcratingreview_form_status_index'
						) );

						if ( ! $exists ) {
							$table->index( 'status', 'cbxmcratingreview_form_status_index' );
						}
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( 'Migration Error (cbxmcratingreview_form indexes): ' . $e->getMessage() );
				}
			}

			//phpcs:enable
		}//end method up

		/**
		 * Drop migration
		 */
		public static function down() {
			$table_name = 'cbxmcratingreview_form';
			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {
					Capsule::schema()->table( $table_name, function ( $table ) {
						$table->dropIndex( 'cbxmcratingreview_form_status_index' );
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( 'Migration Down Error (cbxmcratingreview_form indexes): ' . $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXMcRatingReviewFormAddIndexes
}

if ( isset( $action ) && $action == 'up' ) {
	CBXMcRatingReviewFormAddIndexes::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXMcRatingReviewFormAddIndexes::down();
}