<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXMCRatingReviewScoped\Illuminate\Database\Capsule\Manager as Capsule;

if ( ! class_exists( 'CBXMcRatingReviewAddIndexes' ) ) {
	/**
	 * Class CBXMcRatingReviewAddIndexes
	 * @since 2.0.0
	 */
	class CBXMcRatingReviewAddIndexes {

		/**
		 * Run migration
		 */
		public static function up() {
			global $wpdb;
			$table_name = 'cbxmcratingreview_log';
			$prefixed_table = $wpdb->prefix . $table_name;

			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {

					Capsule::schema()->table( $table_name, function ( $table ) use ( $wpdb, $prefixed_table ) {
						// Add index for post_id if not exists
						/*$index_post = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxmcratingreview_post_id_index' ) );
						if ( ! $index_post ) {
							$table->index( 'post_id', 'cbxmcratingreview_post_id_index' );
						}

						// Add index for user_id if not exists
						$index_user = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxmcratingreview_user_id_index' ) );
						if ( ! $index_user ) {
							$table->index( 'user_id', 'cbxmcratingreview_user_id_index' );
						}

						// Add index for status if not exists
						$index_status = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$prefixed_table} WHERE Key_name = %s", 'cbxmcratingreview_status_index' ) );
						if ( ! $index_status ) {
							$table->index( 'status', 'cbxmcratingreview_status_index' );
						}*/

						$indexes = array(
							'post_id' => 'cbxmcratingreview_post_id_index',
							'user_id' => 'cbxmcratingreview_user_id_index',
							'status'  => 'cbxmcratingreview_status_index',
						);

						//phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						foreach ( $indexes as $column => $index_name ) {
							$exists = $wpdb->get_var( $wpdb->prepare(
								"SHOW INDEX FROM %i WHERE Key_name = %s",
								$prefixed_table,
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
					write_log( 'Migration Error (cbxmcratingreview indexes): ' . $e->getMessage() );
				}
			}
		}//end method up

		/**
		 * Drop migration
		 */
		public static function down() {
			$table_name = 'cbxmcratingreview_log';
			try {
				if ( Capsule::schema()->hasTable( $table_name ) ) {
					Capsule::schema()->table( $table_name, function ( $table ) {
						$table->dropIndex( 'cbxmcratingreview_post_id_index' );
						$table->dropIndex( 'cbxmcratingreview_user_id_index' );
						$table->dropIndex( 'cbxmcratingreview_status_index' );
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( 'Migration Down Error (cbxmcratingreview indexes): ' . $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXMcRatingReviewAddIndexes
}

if ( isset( $action ) && $action == 'up' ) {
	CBXMcRatingReviewAddIndexes::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXMcRatingReviewAddIndexes::down();
}