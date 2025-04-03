<?php

use Illuminate\Database\Capsule\Manager as Capsule;


if ( ! class_exists( 'CreateCBXMcRatingReviewLogTable' ) ) {
	/**
	 * Common migration class for migration table and other tables(codeboxr's plugin or 3rd party if anyone use)
	 *
	 * Class CreateCBXMcRatingReviewLogTable
	 * @since 1.0.0
	 */
	class CreateCBXMcRatingReviewLogTable {

		/**
		 * Run migrations
		 *
		 * @since 1.0.0
		 */
		public static function up() {
			//migrations table create if not exists
			try {
				if ( ! Capsule::schema()->hasTable( 'cbxmcratingreview_log' ) ) {
					Capsule::schema()->create( 'cbxmcratingreview_log', function ( $table ) {
						$table->increments( 'id' );
						$table->integer( 'form_id' )->comment('foreign key of cbxmcratingreview_form table');
						$table->bigInteger( 'post_id' )->comment('foreign key of posts table');
						$table->string( 'post_type' , 50)->comment('post type e.g. post, page, media');
						$table->integer( 'user_id' )->comment('foreign key of users table');
						$table->float( 'score' )->default(0)->comment('user given avg rating, can be half i.e. 3.5');
						$table->string( 'headline' )->nullable()->comment('review short title');
						$table->text( 'comment' )->nullable()->comment('review full desc');
						$table->longtext( 'ratings' )->comment('all criteria details rating');
						$table->longtext( 'questions' )->comment('answer of all questions');
						$table->text( 'attachment' )->nullable()->comment('photos or video url');
						$table->text( 'extraparams' )->nullable()->comment('extra parameters for future new fields');
						$table->string( 'status' , 20)->comment('0 pending, 1 published, 2 unpublished, 3 spam');
						$table->integer( 'mod_by' )->nullable()->comment('foreign key of user table. who last modify this list');
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
				if ( Capsule::schema()->hasTable( 'cbxmcratingreview_log' ) ) {
					return true;
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CreateCBXMcRatingReviewLogTable
}


if ( isset( $action ) && $action == 'up' ) {
	CreateCBXMcRatingReviewLogTable::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CreateCBXMcRatingReviewLogTable::down();
}
