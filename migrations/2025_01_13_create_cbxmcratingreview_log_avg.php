<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use CBXMCRatingReviewScoped\Illuminate\Database\Capsule\Manager as Capsule;


if ( ! class_exists( 'CBXMcRatingReviewCreateAvgLogTable' ) ) {
	/**
	 * Common migration class for migration table and other tables(codeboxr's plugin or 3rd party if anyone use)
	 *
	 * Class CBXMcRatingReviewCreateAvgLogTable
	 * @since 1.0.0
	 */
	class CBXMcRatingReviewCreateAvgLogTable {

		/**
		 * Run migrations
		 *
		 * @since 1.0.0
		 */
		public static function up() {
			//migrations table create if not exists
			try {
				if ( ! Capsule::schema()->hasTable( 'cbxmcratingreview_log_avg' ) ) {
					Capsule::schema()->create( 'cbxmcratingreview_log_avg', function ( $table ) {
						$table->increments( 'id' );
						$table->integer( 'form_id' )->comment('foreign key of cbxmcratingreview_form table');
						$table->bigInteger( 'post_id' )->comment('foreign key of posts table');
						$table->string( 'post_type' , 50)->comment('post type e.g. post, page, media');						
						$table->float( 'avg_rating' )->default(0)->comment('user given rating avg');
						$table->bigInteger( 'total_count' )->default(0)->comment('total user rate for this post');
						$table->text( 'rating_stat' )->nullable()->comment('statistics about how many user give 1, 2, 3, 4, 5 rating');
						$table->longtext( 'ratings' )->comment('all criteria details rating');
						$table->datetime( 'date_created' )->comment('created date');
						$table->datetime( 'date_modified' )->nullable()->comment('modified date');
					} );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method up

		/**
		 * Migration drop
		 *
		 * @return true|void
		 */
		public static function down() {
			try {
				if ( Capsule::schema()->hasTable( 'cbxmcratingreview_log_avg' ) ) {
					return true;
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXMcRatingReviewCreateAvgLogTable
}


if ( isset( $action ) && $action == 'up' ) {
	CBXMcRatingReviewCreateAvgLogTable::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXMcRatingReviewCreateAvgLogTable::down();
}
