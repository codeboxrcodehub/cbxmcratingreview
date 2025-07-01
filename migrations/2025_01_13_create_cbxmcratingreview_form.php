<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Illuminate\Database\Capsule\Manager as Capsule;


if ( ! class_exists( 'CreateCBXMcRatingReviewFormTable' ) ) {
	/**
	 * Common migration class for migration table and other tables(codeboxr's plugin or 3rd party if anyone use)
	 *
	 * Class CreateCBXMcRatingReviewFormTable
	 * @since 1.0.0
	 */
	class CreateCBXMcRatingReviewFormTable {

		/**
		 * Run migrations
		 *
		 * @since 1.0.0
		 */
		public static function up() {
			//migrations table create if not exists
			try {
				if ( ! Capsule::schema()->hasTable( 'cbxmcratingreview_form' ) ) {
					Capsule::schema()->create( 'cbxmcratingreview_form', function ( $table ) {
						$table->increments( 'id' );
						$table->string( 'name' );
						$table->tinyInteger( 'status' );
						$table->longtext( 'custom_criteria' );
						$table->longtext( 'custom_question' );
						//$table->longtext( 'extrafields' )->default('');
						$table->longtext( 'extrafields' )->nullable();
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
				if ( Capsule::schema()->hasTable( 'cbxmcratingreview_form' ) ) {
					return true;
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CreateCBXMcRatingReviewFormTable
}


if ( isset( $action ) && $action == 'up' ) {
	CreateCBXMcRatingReviewFormTable::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CreateCBXMcRatingReviewFormTable::down();
}
