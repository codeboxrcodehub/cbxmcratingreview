<?php

use CBX\MCRatingReview\CBXMCRatingReviewHooks;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use CBX\MCRatingReview\MigrationManage;
use CBX\MCRatingReview\CBXMCRatingReviewMisc;
use CBX\MCRatingReview\Api\CBXRoute;
use CBX\MCRatingReview\CBXMCRatingReviewPublic;
use CBX\MCRatingReview\CBXMCRatingReviewAdmin;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codeboxr.com
 * @since      2.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 * @author     Sabuj Kundu <sabuj@codeboxr.com>
 */
final class CBXMCRatingReview {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  2.0.0
	 */
	private static $instance = null;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->version     = CBXMCRATINGREVIEW_PLUGIN_VERSION;
		$this->plugin_name = CBXMCRATINGREVIEW_PLUGIN_NAME;

		$this->include_files();


		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}//end of constructor

/**
	 * Include necessary files
	 *
	 * @return void
	 * @since 2.0.0
	 */
	private function include_files() {
		require_once __DIR__ . '/../lib/autoload.php';
		include_once __DIR__ . '/CBXMCRatingReviewEmails.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2Text.php';
	}//end method instance

	/**
	 * Define common
	 *
	 * @return void
	 */
	private function define_common_hooks() {
		$helper = new CBXMCRatingReviewHelper();
		$misc   = new CBXMCRatingReviewMisc();
		$route  = new CBXRoute();

		add_action( 'init', [ $helper, 'load_orm' ] );
		add_action( 'init', [ $misc, 'load_plugin_textdomain' ] );
		add_action( 'init', [ $misc, 'load_mailer' ] );

		add_action( 'rest_api_init', [ $route, 'init' ] );
		add_filter( 'script_loader_tag', [ $misc, 'add_module_to_script' ], 10, 3 );
	}//end magic method get

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new CBXMCRatingReviewAdmin();
		$misc         = new CBXMCRatingReviewMisc();

		add_action( 'admin_init', [ $plugin_admin, 'setting_init' ] );
		add_action( 'admin_init', [ $plugin_admin, 'review_delete_after_delete_post_init' ] );

		//create admin menu page
		add_action( 'admin_menu', [ $plugin_admin, 'admin_pages' ] );


		//setting init and add  setting sub menu in setting menu
		add_action( 'wp_ajax_cbxmcratingreview_review_rating_admin_edit', [
			$plugin_admin,
			'review_rating_admin_edit'
		] );

		//add all css and js in backend
		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_scripts' ] );


		//on review publish
		add_action( 'cbxmcratingreview_review_publish', [ $plugin_admin, 'review_publish_adjust_avg' ] );
		add_action( 'cbxmcratingreview_review_unpublish', [ $plugin_admin, 'review_unpublish_adjust_avg' ] );
		//on review delete extra process
		add_action( 'cbxmcratingreview_review_delete_after', [ $plugin_admin, 'review_delete_after' ] );


		//rating form delete extra process
		add_action( 'cbxmcratingreview_form_delete_after', [ $plugin_admin, 'form_delete_after' ] );

		//on user delete
		add_action( 'delete_user', [ $plugin_admin, 'review_delete_after_delete_user' ] );
		add_action( 'admin_init', [ $plugin_admin, 'save_email_setting' ] );

		// update hooks
		add_action( 'plugins_loaded', [ $plugin_admin, 'plugin_upgrader_process_complete' ] );
		add_action( 'admin_notices', [ $plugin_admin, 'plugin_activate_upgrade_notices' ] );
		add_filter( 'plugin_action_links_' . CBXMCRATINGREVIEW_BASE_NAME, [ $plugin_admin, 'plugin_action_links' ] );
		add_filter( 'plugin_row_meta', [ $plugin_admin, 'plugin_row_meta' ], 10, 4 );
		add_action( 'after_plugin_row_cbxmcratingreviewpro/cbxmcratingreviewpro.php', [
			$plugin_admin,
			'custom_message_after_plugin_row_proaddon'
		], 10, 2 );

		add_action( 'activated_plugin', [ $plugin_admin, 'check_pro_addon' ] );
		add_action( 'init', [ $plugin_admin, 'check_pro_addon' ] );

		add_filter( 'robots_txt', [ $misc, 'custom_robots_txt' ] );
	}//end magic mathod set

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$public = new CBXMCRatingReviewPublic();

		add_action( 'init', [ $public, 'init_shortcodes' ] );

		//review rating entry via ajax
		add_action( 'wp_ajax_cbxmcratingreview_review_rating_frontend_submit', [
			$public,
			'review_rating_frontend_submit'
		] );


		//ajax post reviews load more
		add_action( 'wp_ajax_cbxmcratingreview_post_more_reviews', [ $public, 'post_more_reviews_ajax_load' ] );
		add_action( 'wp_ajax_nopriv_cbxmcratingreview_post_more_reviews', [
			$public,
			'post_more_reviews_ajax_load'
		] );

		//ajax review filter
		add_action( 'wp_ajax_cbxmcratingreview_post_filter_reviews', [ $public, 'post_filter_reviews_ajax_load' ] );
		add_action( 'wp_ajax_nopriv_cbxmcratingreview_post_filter_reviews', [
			$public,
			'post_filter_reviews_ajax_load'
		] );

		//widget
		add_action( 'widgets_init', [ $public, 'init_register_widget' ] );

		add_action( 'wp_enqueue_scripts', [ $public, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $public, 'enqueue_scripts' ] );


		//ajax review delete from frontend
		add_action( 'wp_ajax_cbxmcratingreview_review_delete', [ $public, 'review_delete_ajax' ] );


		//special care of review edit for adjustment
		add_action( 'cbxmcratingreview_review_update_without_status', [
			$public,
			'cbxmcratingreview_review_update_without_status_adjust_postavg'
		], 10, 3 );

		add_action( 'cbxmcratingreview_review_list_item_after', [
			$public,
			'cbxmcratingreview_single_review_toolbar'
		], 8, 1 );

		add_action( 'cbxmcratingreview_review_list_item_toolbar_right', [
			$public,
			'cbxmcratingreview_single_review_delete_button'
		] );

		add_filter( 'the_content', [ $public, 'the_content_auto_integration' ] );
	}//end method clone

	/**
	 * Singleton Instance.
	 *
	 * Ensures only one instance of CBXMCRatingReview is loaded or can be loaded.
	 *
	 * @return self Main instance.
	 * @since  2.0.0
	 * @static
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}//end method wakeup

	/**
	 * Autoload inaccessible or non-existing properties on demand.
	 *
	 * @param $key
	 *
	 * @return void
	 */
	public function __get( $key ) {
		if ( in_array( $key, [ 'mailer' ], true ) ) {
			return $this->$key();
		}
	}//end method include_files

	/**
	 * Set the value of an inaccessible or non-existing property.
	 *
	 * @param string $key Property name.
	 * @param mixed $value Property value.
	 */
	public function __set( string $key, $value ) {
		if ( property_exists( $this, $key ) ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
			trigger_error( 'Cannot access private property CBXMCRatingReview::$' . esc_html( $key ), E_USER_ERROR );
		} else {
			$this->$key = $value;
		}
	}//end method mailer

	/**
	 * Cloning is forbidden.
	 *
	 * @since 2.0.0
	 */
	public function __clone() {
		cbxmcratingreview_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'cbxmcratingreview' ), '2.0.0' );
	}//end method define_common_hooks

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 2.0.0
	 */
	public function __wakeup() {
		cbxmcratingreview_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'cbxmcratingreview' ), '2.0.0' );
	}//end method define_admin_hooks

	/**
	 * Email Class.
	 *
	 * @return CBXMCRatingReviewEmails
	 */
	public function mailer() {
		return cbxmcratingreview_mailer();
	}//end method define_public_hooks
}// end class CBXMCRatingReview