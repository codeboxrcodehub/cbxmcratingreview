<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXMCRatingReviewScoped\Illuminate\Database\Capsule\Manager as Capsule;

if ( ! class_exists( 'CBXMcRatingReviewAvgAddIndexes' ) ) {
	/**
	 * Class CBXMcRatingReviewAvgAddIndexes
	 * @since 2.0.0
	 */
	class CBXMcRatingReviewAvgAddIndexes {

		/**
		 * Run migration
		 */
		public static function up() {
			global $wpdb;
			$table_name = 'cbxmcratingreview_log_avg';
			$prefixed_table = $wpdb->prefix . $table_name;

			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {
					Capsule::schema()->table( $table_name, function ( $table ) use ( $wpdb, $prefixed_table ) {
						// Add index for post_id if not exists
						/*$index_post = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxmcratingreview_avg_post_id_index' ) );
						if ( ! $index_post ) {
							$table->index( 'post_id', 'cbxmcratingreview_avg_post_id_index' );
						}*/

						// Define indexes to check
						$indexes = array(
							'post_id' => 'cbxmcratingreview_avg_post_id_index',
						);

						$escaped_table = esc_sql( $prefixed_table );

						//phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						foreach ( $indexes as $column => $index_name ) {
							$exists = $wpdb->get_var( $wpdb->prepare(
								"SHOW INDEX FROM " . $escaped_table . " WHERE Key_name = %s",
								$index_name
							) );

							if ( ! $exists ) {
								$table->index( $column, $index_name );
							}
						}
						//phpcs:enable
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( 'Migration Error (cbxmcratingreview_avg indexes): ' . $e->getMessage() );
				}
			}
		}//end method up

		/**
		 * Drop migration
		 */
		public static function down() {
			$table_name = 'cbxmcratingreview_log_avg';
			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {
					Capsule::schema()->table( $table_name, function ( $table ) {
						$table->dropIndex( 'cbxmcratingreview_avg_post_id_index' );
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( 'Migration Down Error (cbxmcratingreview_avg indexes): ' . $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXMcRatingReviewAvgAddIndexes
}

if ( isset( $action ) && $action == 'up' ) {
	CBXMcRatingReviewAvgAddIndexes::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXMcRatingReviewAvgAddIndexes::down();
}