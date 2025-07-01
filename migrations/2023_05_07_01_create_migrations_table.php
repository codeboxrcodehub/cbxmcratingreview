<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Illuminate\Database\Capsule\Manager as Capsule;


if ( ! class_exists( 'CreateCBXMigrationsTable' ) ) {
	/**
	 * Common migration class for migration table and other tables(codeboxr's plugin or 3rd party if anyone use)
	 *
	 * Class CreateCBXMigrationsTable
	 * @since 1.0.0
	 */
	class CreateCBXMigrationsTable {

		/**
		 * Run migrations
		 *
		 * @since 1.0.0
		 */
		public static function up() {
			//migrations table create if not exists
			try {
				if ( ! Capsule::schema()->hasTable( 'cbxmigrations' ) ) {
					Capsule::schema()->create( 'cbxmigrations', function ( $table ) {
						$table->increments( 'id' );
						$table->string( 'migration' );
						$table->integer( 'batch' );
						$table->string( 'plugin' );
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
				if ( Capsule::schema()->hasTable( 'cbxmigrations' ) ) {
					return true;
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CreateCBXMigrationsTable
}


if ( isset( $action ) && $action == 'up' ) {
	CreateCBXMigrationsTable::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CreateCBXMigrationsTable::down();
}
