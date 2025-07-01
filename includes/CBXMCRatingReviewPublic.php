<?php
namespace CBX\MCRatingReview;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/public
 * @author     Sabuj Kundu <sabuj@codeboxr.com>
 */
class CBXMCRatingReviewPublic {

	public $settings;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct() {
		$this->version  = CBXMCRATINGREVIEW_PLUGIN_VERSION;
		$this->settings = new CBXMCRatingReviewSettings();
	}//end of constructor

	/**
	 * Init all shortcodes
	 */
	public function init_shortcodes() {
		//show rating form
		add_shortcode( 'cbxmcratingreview_reviewform', [ $this, 'reviewform_shortcode' ] );
		//show rating form avg by post id
		add_shortcode( 'cbxmcratingreview_postavgrating', [ $this, 'postavgrating_shortcode' ] );
		//show ratings by post id
		add_shortcode( 'cbxmcratingreview_postreviews', [ $this, 'postreviews_shortcode' ] );

		//dashboard, edit and single review sharing page
		add_shortcode( 'cbxmcratingreview_userdashboard', [ $this, 'userdashboard_shortcode' ] );
		add_shortcode( 'cbxmcratingreview_singlereview', [ $this, 'singlereview_shortcode' ] );
		add_shortcode( 'cbxmcratingreview_editreview', [ $this, 'editreview_shortcode' ] );


		//widget compatible shortcodes
		add_shortcode( 'cbxmcratingreviewmrposts', [ $this, 'cbxmcratingreviewmrposts_shortcode' ] );
		add_shortcode( 'cbxmcratingreviewlratings', [ $this, 'cbxmcratingreviewlratings_shortcode' ] );
	}//end method init_shortcodes

	/**
	 * Init all widgets
	 */
	public function init_register_widget() {
		require_once CBXMCRATINGREVIEW_ROOT_PATH . 'includes/Widgets/Classic/CBXMCRatingReviewLRatingsWidget.php';
		require_once CBXMCRATINGREVIEW_ROOT_PATH . 'includes/Widgets/Classic/CBXMCRatingReviewMRPostsWidget.php';

		register_widget( 'CBXMCRatingReviewLRatingsWidget' ); //latest ratings widget
		register_widget( 'CBXMCRatingReviewMRPostsWidget' );  //most rated post widget
	}//end method init_register_widget

	/**
	 * Rating Form shortcode callback
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function reviewform_shortcode( $atts ) {
		$settings = $this->settings;

		global $post;

		$post_id      = $post->ID;
		$default_form = absint( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts(
			[
				'form_id' => intval( $default_form ),
				'post_id' => $post_id,
			], $atts, 'cbxmcratingreview_reviewform' );


		$form_id = isset( $atts['form_id'] ) ? intval( $atts['form_id'] ) : 0;
		$post_id = isset( $atts['post_id'] ) ? intval( $atts['post_id'] ) : 0;

		if ( function_exists( 'cbxmcratingreview_reviewformRender' ) ) {
			return cbxmcratingreview_reviewformRender( $form_id, $post_id );
		}

		return '';
	}//end method reviewform_shortcode

	/**
	 * Post avg info shortcode call back
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function postavgrating_shortcode( $atts ) {
		global $post;

		$post_id      = $post->ID;
		$settings     = new CBXMCRatingReviewSettings();
		$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts(
			[
				'form_id' => $default_form,
				'post_id' => $post_id,
				'details' => 0
			], $atts, 'cbxmcratingreview_postavgrating' );

		$form_id = isset( $atts['form_id'] ) ? intval( $atts['form_id'] ) : 0;
		$post_id = isset( $atts['post_id'] ) ? intval( $atts['post_id'] ) : 0;
		$details = isset( $atts['details'] ) ? intval( $atts['details'] ) : 0;

		if ( $details ) {
			if ( function_exists( 'cbxmcratingreview_postAvgDetailsRatingRender' ) ) {
				return cbxmcratingreview_postAvgDetailsRatingRender( $form_id, $post_id );
			}
		} else {
			if ( function_exists( 'cbxmcratingreview_postAvgRatingRender' ) ) {
				return cbxmcratingreview_postAvgRatingRender( $form_id, $post_id );
			}
		}

		return '';
	}//end method postavgrating_shortcode

	/**
	 * Post reviews shortcode callback
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function postreviews_shortcode( $atts ) {
		global $post;

		$post_id = $post->ID;

		$settings            = new CBXMCRatingReviewSettings();
		$per_page_default    = absint( $settings->get_field( 'default_per_page', 'cbxmcratingreview_common_config', 10 ) );
		$show_filter_default = absint( $settings->get_field( 'show_review_filter', 'cbxmcratingreview_common_config', 1 ) );
		$default_form        = absint( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts(
			[
				'form_id'     => $default_form,
				'post_id'     => $post_id,
				'orderby'     => 'id',   //id, total_count, post_id
				'order'       => 'DESC', //DESC, ASC,
				'score'       => '',
				'perpage'     => $per_page_default,
				'show_filter' => $show_filter_default,
				'show_more'   => 1
			], $atts, 'cbxmcratingreview_postreviews' );


		$form_id     = isset( $atts['form_id'] ) ? absint( $atts['form_id'] ) : 0;
		$post_id     = isset( $atts['post_id'] ) ? absint( $atts['post_id'] ) : 0;
		$order_by    = isset( $atts['orderby'] ) ? esc_attr( $atts['orderby'] ) : 'id';
		$order       = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
		$score       = isset( $atts['score'] ) ? esc_attr( $atts['score'] ) : '';
		$per_page    = isset( $atts['perpage'] ) ? absint( $atts['perpage'] ) : $per_page_default;
		$show_filter = isset( $atts['show_filter'] ) ? absint( $atts['show_filter'] ) : $show_filter_default;
		$show_more   = isset( $atts['show_more'] ) ? absint( $atts['show_more'] ) : 1;

		$output = '';

		if ( $show_filter ) {
			if ( function_exists( 'cbxmcratingreview_postReviewsFilterRender' ) ) {
				$output .= cbxmcratingreview_postReviewsFilterRender( $form_id, $post_id, $per_page, 1, $score, $order_by, $order );
			}
		}

		if ( function_exists( 'cbxmcratingreview_postReviewsRender' ) ) {
			$output .= cbxmcratingreview_postReviewsRender( $form_id, $post_id, $per_page, 1, $score, $order_by, $order, $show_more );
		}

		return $output;
	}//end method postreviews_shortcode


	/**
	 * User rating frontend dashboard
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function userdashboard_shortcode( $atts ) {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		$atts = shortcode_atts( [], $atts, 'cbxmcratingreview_userdashboard' );

		$settings = new CBXMCRatingReviewSettings();

		$output = '';

		if ( is_user_logged_in() ) {
			$ver          = $this->version;
			$css_url_part = CBXMCRATINGREVIEW_ROOT_URL . 'assets/css/';


			wp_register_style( 'cbxmcratingreview-builder', $css_url_part . 'cbxmcratingreview-builder.css', [], $ver, 'all' );
			wp_register_style( 'cbxmcratingreview-public', $css_url_part . 'cbxmcratingreview-public.css', [], $ver, 'all' );

			wp_enqueue_style( 'cbxmcratingreview-builder' );
			wp_enqueue_style( 'cbxmcratingreview-public' );

			$current_user      = wp_get_current_user();
			$blog_id           = is_multisite() ? get_current_blog_id() : null;
			$js_url_part_build = CBXMCRATINGREVIEW_ROOT_URL . 'assets/js/build/';

			$js_translations = CBXMCRatingReviewHelper::cbxmcratingreview_log_builder_js_translation( $current_user, $blog_id );

			if ( defined( 'CBXMCRATINGREVIEW_DEV_MODE' ) && CBXMCRATINGREVIEW_DEV_MODE ) {
				wp_register_script( 'cbxmcratingreview_log_vue_dev', 'http://localhost:8880/assets/vuejs/apps/front/cbxmcratingreviewlog.js', [], $ver, true );
				wp_localize_script( 'cbxmcratingreview_log_vue_dev', 'cbxmcratingreview_vue_var', $js_translations );

				wp_enqueue_script( 'cbxmcratingreview_log_vue_dev' );
			} else {
				wp_register_script( 'cbxmcratingreview_log_vue_main', $js_url_part_build . 'cbxmcratingreviewfrontlog.js', [], $ver, true );
				wp_localize_script( 'cbxmcratingreview_log_vue_main', 'cbxmcratingreview_vue_var', $js_translations );

				wp_enqueue_script( 'cbxmcratingreview_log_vue_main' );
			}

			//$output .= '<div class="cbx-chota cbxmcratingreview-frontend-manager-wrapper" id="cbxmcratingreview-review-public"></div>';
			$output .= cbxmcratingreview_get_template_html( 'rating-review-dashboard-user.php', [
				'settings' => $settings,
			] );

		} else {
			$guest_login_form = esc_attr( $settings->get_field( 'guest_login_form', 'cbxmcratingreview_common_config', 'wordpress' ) );

			if ( $guest_login_form != 'off' ) {
				wp_enqueue_style( 'cbxmcratingreview-public' );
				$output .= cbxmcratingreview_get_template_html( 'rating-review-dashboard-guest.php', [
					'settings' => $settings,
				] );
			}

		}

		return $output;
	}//end method userdashboard_shortcode

	/**
	 * Single review render using shortcode
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function singlereview_shortcode( $atts ) {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts(
			[
				'review_id' => 0,
			], $atts, 'cbxmcratingreview_singlereview' );

		$single_review_html = '<div class="cbx-chota"><div class="container">';

		if ( function_exists( 'cbxmcratingreview_singleReviewRender' ) ) {
			//at first take from shortcode
			$review_id = isset( $atts['review_id'] ) ? absint( $atts['review_id'] ) : 0;
			if ( $review_id == 0 ) {
				//now take from url
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$review_id = isset( $_GET['review_id'] ) ? absint( $_GET['review_id'] ) : 0;
			}

			if ( $review_id > 0 ) {
				$post_review = cbxmcratingreview_singleReview( $review_id );
				$post_id     = absint( $post_review['post_id'] );
				$post_title  = get_the_title( $post_id );
				$post_link   = get_permalink( $post_id );

				$single_review_html .= '<p>' . esc_html__( 'Reviewed', 'cbxmcratingreview' ) . ' : <a target="_blank" href="' . esc_url( $post_link ) . '">' . esc_attr( $post_title ) . '</a></p>';

				$single_review_html .= '<ul class="cbxmcratingreview_review_list_items">';
				$single_review_html .= '<li id="cbxmcratingreview_review_list_item_' . absint( $review_id ) . '" class="' . apply_filters( 'cbxmcratingreview_review_list_item_class', 'cbxmcratingreview_review_list_item' ) . '">';
				$single_review_html .= cbxmcratingreview_singleReviewRender( $post_review );

				$single_review_html .= '</li>';
				$single_review_html .= '</ul>';
			} else {
				$single_review_html .= '<div class="alert alert-danger" role="alert">' . esc_html__( 'Sorry, review not found or unpublished', 'cbxmcratingreview' ) . '</div>';
			}
		}

		$single_review_html .= '</div></div>';

		return $single_review_html;
	}//end method singlereview_shortcode

	/**
	 * Single review edit render using shortcode
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function editreview_shortcode( $atts ) {
		return esc_html__( 'This shortcode has been deprecated and the purpose of this shortcode is served by the cbxmcratingreview_userdashboard shortcode.', 'cbxmcratingreview' );
	}//end method editreview_shortcode


	/**
	 * Shortcode callback for most rated post
	 */
	public function cbxmcratingreviewmrposts_shortcode( $atts ) {
		$settings = new CBXMCRatingReviewSettings();

		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts(
			[
				'title'   => '', //default empty
				'form_id' => 0, //if form id is given then only reviews for that form wil show
				'scope'   => 'shortcode',
				'limit'   => 10,
				'orderby' => 'avg_rating', //avg_rating, total_count, post_id
				'order'   => 'DESC',       //DESC, ASC,
				'type'    => 'post'
			], $atts, 'cbxmcratingreviewmrposts' );

		$scope    = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? sanitize_text_field( wp_unslash( $atts['scope'] ) ) : 'shortcode';
		$form_id  = isset( $atts['form_id'] ) ? absint( $atts['form_id'] ) : 0;
		$limit    = isset( $atts['limit'] ) ? absint( $atts['limit'] ) : 10;
		$order_by = isset( $atts['orderby'] ) ? sanitize_text_field( wp_unslash( $atts['orderby'] ) ) : 'avg_rating';
		$order    = isset( $atts['order'] ) ? sanitize_text_field( wp_unslash( $atts['order'] ) ) : 'DESC';
		$type     = isset( $atts['type'] ) ? sanitize_text_field( wp_unslash( $atts['type'] ) ) : 'post';
		$title    = isset( $atts['title'] ) ? sanitize_text_field( wp_unslash( $atts['title'] ) ) : '';

		cbxmcratingreview_AddJsCss();

		$data_posts = cbxmcratingreview_most_rated_posts( $form_id, $limit, $order_by, $order, $type ); //variable name $data_posts  is important for template files

		return cbxmcratingreview_get_template_html( 'widgets/most_rated_posts.php', [
				'title'      => $title,
				'form_id'    => $form_id,
				'orderby'    => $order_by,
				'order'      => $order,
				'settings'   => $settings,
				'data_posts' => $data_posts,
				'scope'      => $scope,
				'limit'      => $limit,
				'type'       => $type,
				'atts'       => $atts
			]
		);
	}//end method cbxmcratingreviewmrposts_shortcode

	/**
	 * Shortcode callback for latest ratings
	 */
	public function cbxmcratingreviewlratings_shortcode( $atts ) {
		$settings = new CBXMCRatingReviewSettings();

		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts(
			[
				'title'   => '', //default empty
				'form_id' => 0, //if form id is given then only reviews for that form wil show
				'scope'   => 'shortcode',
				'limit'   => 10,
				'orderby' => 'id', //id, score, post_id
				'order'   => 'DESC',
				'type'    => 'post'
			], $atts, 'cbxmcratingreviewlratings' );

		$scope    = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? sanitize_text_field( wp_unslash( $atts['scope'] ) ) : 'shortcode';
		$form_id  = isset( $atts['form_id'] ) ? absint( $atts['form_id'] ) : 0;
		$limit    = isset( $atts['limit'] ) ? absint( $atts['limit'] ) : 10;
		$order_by = isset( $atts['orderby'] ) ? sanitize_text_field( wp_unslash( $atts['orderby'] ) ) : 'id'; //id, score, post_id
		$order    = isset( $atts['order'] ) ? sanitize_text_field( wp_unslash( $atts['order'] ) ) : 'DESC';
		$type     = isset( $atts['type'] ) ? sanitize_text_field( wp_unslash( $atts['type'] ) ) : 'post';
		$title    = isset( $atts['title'] ) ? sanitize_text_field( wp_unslash( $atts['title'] ) ) : '';


		cbxmcratingreview_AddJsCss();

		$data_posts = cbxmcratingreview_lastest_ratings( $form_id, $limit, $order_by, $order, $type ); //variable name $data_posts  is important for template files

		return cbxmcratingreview_get_template_html( 'widgets/latest_ratings.php', [
				'title'      => $title,
				'form_id'    => $form_id,
				'orderby'    => $order_by,
				'order'      => $order,
				'settings'   => $settings,
				'data_posts' => $data_posts,
				'scope'      => $scope,
				'limit'      => $limit,
				'type'       => $type,
				'atts'       => $atts
			]
		);
	}//end method cbxmcratingreviewlratings_shortcode

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		do_action( 'cbxmcratingreview_reg_styles_before' );

		$ratingform_css_dep = [];
		$common_css_dep     = [];

		$ver = $this->version;

		$vendors_url_part = CBXMCRATINGREVIEW_ROOT_URL . 'assets/vendors/';
		$css_url_part     = CBXMCRATINGREVIEW_ROOT_URL . 'assets/css/';

		// Enqueue Quill CSS
		wp_enqueue_style( 'quill-css', 'https://cdn.quilljs.com/1.3.6/quill.snow.css', [], $ver );


		wp_register_style( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/style.css', [], $ver );
		//wp_register_style( 'jquery-ui', $vendors_url_part . 'ui-lightness/jquery-ui.min.css', [], $ver );
		wp_register_style( 'jquery-cbxmcratingreview-raty', $css_url_part . 'jquery.cbxmcratingreview_raty.css', [], $ver, 'all' );


		//$ratingform_css_dep[] = 'jquery-ui';
		$ratingform_css_dep[] = 'awesome-notifications';
		$ratingform_css_dep[] = 'jquery-cbxmcratingreview-raty';

		$common_css_dep[] = 'awesome-notifications';
		$common_css_dep[] = 'jquery-cbxmcratingreview-raty';

		$common_css_dep = apply_filters( 'cbxmcratingreview_common_css_dep', $common_css_dep );

		do_action( 'cbxmcratingreview_reg_styles' );

		wp_register_style( 'cbxmcratingreview-public', $css_url_part . 'cbxmcratingreview-public.css', $common_css_dep, $ver, 'all' );
		$ratingform_css_dep[] = 'cbxmcratingreview-public';

		$ratingform_css_dep = apply_filters( 'cbxmcratingreview_ratingform_css_dep', $ratingform_css_dep );

		wp_register_style( 'cbxmcratingreview-ratingform', $css_url_part . 'cbxmcratingreview-ratingform.css', $ratingform_css_dep, $ver, 'all' );

		do_action( 'cbxmcratingreview_reg_styles_after' );
	}//end method enqueue_styles

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$settings = $this->settings;
		$ver      = $this->version;

		$footer = [
			'in_footer' => true,
		];

		$vendors_url_part = CBXMCRATINGREVIEW_ROOT_URL . 'assets/vendors/';
		$js_url_build     = CBXMCRATINGREVIEW_ROOT_URL . 'assets/js/build/';
		$js_url_vanila    = CBXMCRATINGREVIEW_ROOT_URL . 'assets/js/vanila/';

		$require_headline = absint( $settings->get_field( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
		$require_comment  = absint( $settings->get_field( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );
		$half_rating      = absint( $settings->get_field( 'half_rating', 'cbxmcratingreview_common_config', 0 ) );


		$ratingform_js_dep     = [];
		$ratingeditform_js_dep = [];
		$common_js_dep         = [];


		do_action( 'cbxmcratingreview_reg_scripts_before' );

		//common for everywhere
		wp_register_script( 'cbxmcratingreview-events', $js_url_vanila . 'cbxmcratingreview-events.js', [], $ver, $footer );
		wp_register_script( 'jquery-cbxmcratingreview-raty', $js_url_vanila . 'jquery.cbxmcratingreview_raty.js', [ 'jquery' ], $ver, $footer );


		wp_register_script( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/script.js', [], $ver, $footer );
		wp_register_script( 'jquery-validate', $js_url_vanila . 'jquery.validate.min.js', [ 'jquery' ], $ver, $footer );

		$ratingform_js_dep[] = 'cbxmcratingreview-events';
		$ratingform_js_dep[] = 'jquery';
		//$ratingform_js_dep[] = 'jquery-ui-datepicker';
		$ratingform_js_dep[] = 'jquery-cbxmcratingreview-raty';
		$ratingform_js_dep[] = 'jquery-validate';
		$ratingform_js_dep[] = 'awesome-notifications';

		$ratingeditform_js_dep[] = 'cbxmcratingreview-events';
		$ratingeditform_js_dep[] = 'jquery';
		//$ratingeditform_js_dep[] = 'jquery-ui-datepicker';
		$ratingeditform_js_dep[] = 'jquery-cbxmcratingreview-raty';
		$ratingeditform_js_dep[] = 'jquery-validate';
		$ratingeditform_js_dep[] = 'awesome-notifications';


		$common_js_dep[] = 'cbxmcratingreview-events';
		$common_js_dep[] = 'jquery';
		$common_js_dep[] = 'awesome-notifications';
		$common_js_dep[] = 'jquery-cbxmcratingreview-raty';


		do_action( 'cbxmcratingreview_reg_scripts' );

		$common_js_dep = apply_filters( 'cbxmcratingreview_common_js_dep', $common_js_dep );

		wp_register_script( 'cbxmcratingreview-public', $js_url_vanila . 'cbxmcratingreview-public.js', $common_js_dep, $ver, $footer );

		$ratingform_js_dep[]     = 'cbxmcratingreview-public';
		$ratingeditform_js_dep[] = 'cbxmcratingreview-public';

		$ratingform_js_dep     = apply_filters( 'cbxmcratingreview_ratingform_js_dep', $ratingform_js_dep );
		$ratingeditform_js_dep = apply_filters( 'cbxmcratingreview_editform_js_dep', $ratingeditform_js_dep );

		wp_register_script( 'cbxmcratingreview-ratingform', $js_url_vanila . 'cbxmcratingreview-ratingform.js', $ratingform_js_dep, $ver, $footer );
		wp_register_script( 'cbxmcratingreview-ratingeditform', $js_url_vanila . 'cbxmcratingreview-ratingform-frontedit.js', $ratingeditform_js_dep, $ver, $footer);


		// Localize the script with new data
		$cbxmcratingreview_public_ratingform_js_vars = apply_filters( 'cbxmcratingreview_public_ratingform_js_vars', [
			'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
			'nonce'                    => wp_create_nonce( 'cbxmcratingreview' ),
			'rating'                   => [
				'half_rating' => $half_rating,
				'cancelHint'  => esc_html__( 'Cancel this rating!', 'cbxmcratingreview' ),
				'hints'       => CBXMCRatingReviewHelper::ratingHints(),
				'noRatedMsg'  => esc_html__( 'Not rated yet!', 'cbxmcratingreview' ),
				'img_path'    => apply_filters( 'cbxmcratingreview_star_image_url', CBXMCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
			],
			'validation'               => [
				'required'                        => esc_html__( 'This field is required.', 'cbxmcratingreview' ),
				'remote'                          => esc_html__( 'Please fix this field.', 'cbxmcratingreview' ),
				'email'                           => esc_html__( 'Please enter a valid email address.', 'cbxmcratingreview' ),
				'url'                             => esc_html__( 'Please enter a valid URL.', 'cbxmcratingreview' ),
				'date'                            => esc_html__( 'Please enter a valid date.', 'cbxmcratingreview' ),
				'dateISO'                         => esc_html__( 'Please enter a valid date ( ISO ).', 'cbxmcratingreview' ),
				'number'                          => esc_html__( 'Please enter a valid number.', 'cbxmcratingreview' ),
				'digits'                          => esc_html__( 'Please enter only digits.', 'cbxmcratingreview' ),
				'equalTo'                         => esc_html__( 'Please enter the same value again.', 'cbxmcratingreview' ),
				'maxlength'                       => esc_html__( 'Please enter no more than {0} characters.', 'cbxmcratingreview' ),
				'minlength'                       => esc_html__( 'Please enter at least {0} characters.', 'cbxmcratingreview' ),
				'rangelength'                     => esc_html__( 'Please enter a value between {0} and {1} characters long.', 'cbxmcratingreview' ),
				'range'                           => esc_html__( 'Please enter a value between {0} and {1}.', 'cbxmcratingreview' ),
				'max'                             => esc_html__( 'Please enter a value less than or equal to {0}.', 'cbxmcratingreview' ),
				'min'                             => esc_html__( 'Please enter a value greater than or equal to {0}.', 'cbxmcratingreview' ),
				'recaptcha'                       => esc_html__( 'Please check the captcha.', 'cbxmcratingreview' ),
				'cbxmcratingreview_multicheckbox' => esc_html__( 'Please select at least one option', 'cbxmcratingreview' ),
				'form_invalid'                    => esc_html__( 'Rating/review form is not valid, please check all fields', 'cbxmcratingreview' )
			],
			'are_you_sure_global'      => esc_html__( 'Are you sure?', 'cbxmcratingreview' ),
			'are_you_sure_delete_desc' => esc_html__( 'Once you delete, it\'s gone forever. You can not revert it back.', 'cbxmcratingreview' ),
			'awn_options'              => [
				'tip'           => esc_html__( 'Tip', 'cbxmcratingreview' ),
				'info'          => esc_html__( 'Info', 'cbxmcratingreview' ),
				'success'       => esc_html__( 'Success', 'cbxmcratingreview' ),
				'warning'       => esc_html__( 'Attention', 'cbxmcratingreview' ),
				'alert'         => esc_html__( 'Error', 'cbxmcratingreview' ),
				'async'         => esc_html__( 'Loading', 'cbxmcratingreview' ),
				'confirm'       => esc_html__( 'Confirmation', 'cbxmcratingreview' ),
				'confirmOk'     => esc_html__( 'OK', 'cbxmcratingreview' ),
				'confirmCancel' => esc_html__( 'Cancel', 'cbxmcratingreview' )
			],
			'review_common_config'     => [
				'require_headline' => $require_headline,
				'require_comment'  => $require_comment,
			],
			'sort_text'                => esc_html__( 'Drag and Sort', 'cbxmcratingreview' ),
			'forms'                    => []
		] );

		$cbxmcratingreview_public_common_js_vars = apply_filters( 'cbxmcratingreview_public_common_js_vars', [
			'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
			'nonce'                    => wp_create_nonce( 'cbxmcratingreview' ),
			'are_you_sure_global'      => esc_html__( 'Are you sure?', 'cbxmcratingreview' ),
			'are_you_sure_delete_desc' => esc_html__( 'Once you delete, it\'s gone forever. You can not revert it back.', 'cbxmcratingreview' ),
			'awn_options'              => [
				'tip'           => esc_html__( 'Tip', 'cbxmcratingreview' ),
				'info'          => esc_html__( 'Info', 'cbxmcratingreview' ),
				'success'       => esc_html__( 'Success', 'cbxmcratingreview' ),
				'warning'       => esc_html__( 'Attention', 'cbxmcratingreview' ),
				'alert'         => esc_html__( 'Error', 'cbxmcratingreview' ),
				'async'         => esc_html__( 'Loading', 'cbxmcratingreview' ),
				'confirm'       => esc_html__( 'Confirmation', 'cbxmcratingreview' ),
				'confirmOk'     => esc_html__( 'OK', 'cbxmcratingreview' ),
				'confirmCancel' => esc_html__( 'Cancel', 'cbxmcratingreview' )
			],
			'rating'                   => [
				'half_rating' => $half_rating,
				'cancelHint'  => esc_html__( 'Cancel this rating!', 'cbxmcratingreview' ),
				'hints'       => CBXMCRatingReviewHelper::ratingHints(),
				'noRatedMsg'  => esc_html__( 'Not rated yet!', 'cbxmcratingreview' ),
				'img_path'    => apply_filters( 'cbxmcratingreview_star_image_url', CBXMCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
			],
			'no_reviews_found_html'    => '<li class="' . apply_filters( 'cbxmcratingreview_review_list_item_class_notfound_class', 'cbxmcratingreview_review_list_item cbxmcratingreview_review_list_item_notfound' ) . '"><p class="no_reviews_found">' . esc_html__( 'No reviews yet!', 'cbxmcratingreview' ) . '</p>
				</li>',
			'load_more_text'           => esc_html__( 'Load More', 'cbxmcratingreview' ),
			'load_more_busy_text'      => esc_html__( 'Loading next page ...', 'cbxmcratingreview' ),
			'delete_confirm'           => esc_html__( 'Are you sure to delete your review, this processs can not be undone ?', 'cbxmcratingreview' ),
			'delete_text'              => esc_html__( 'Delete', 'cbxmcratingreview' ),
			'delete_error'             => esc_html__( 'Sorry! delete failed!', 'cbxmcratingreview' ),
		] );


		// Enqueue Quill JS
		wp_enqueue_script( 'quill', 'https://cdn.quilljs.com/1.3.6/quill.min.js', [ 'jquery' ], $ver, $footer );

		wp_localize_script( 'cbxmcratingreview-public', 'cbxmcratingreview_public', $cbxmcratingreview_public_common_js_vars );
		wp_localize_script( 'cbxmcratingreview-ratingform', 'cbxmcratingreview_ratingform', $cbxmcratingreview_public_ratingform_js_vars );
		wp_localize_script( 'cbxmcratingreview-ratingeditform', 'cbxmcratingreview_ratingeditform', $cbxmcratingreview_public_ratingform_js_vars );


		do_action( 'cbxmcratingreview_reg_scripts_after' );
	}//end method enqueue_scripts


	/**
	 * Add all common js and css needed for review and rating
	 */
	public function enqueue_common_js_css_rating() {
		do_action( 'cbxmcratingreview_enq_common_js_css_before' );

		// enqueue styles
		wp_enqueue_style( 'awesome-notifications' );
		wp_enqueue_style( 'jquery-cbxmcratingreview-raty' );


		// enqueue scripts
		wp_enqueue_script( 'cbxmcratingreview-events' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'awesome-notifications' );
		wp_enqueue_script( 'jquery-cbxmcratingreview-raty' );

		do_action( 'cbxmcratingreview_enq_common_js_css' );

		wp_enqueue_style( 'cbxmcratingreview-public' );
		wp_enqueue_script( 'cbxmcratingreview-public' );

		do_action( 'cbxmcratingreview_enq_common_js_css_after' );
	}//end method enqueue_common_js_css_rating

	/**
	 * Add all js and css needed for review submit form
	 */
	public function enqueue_ratingform_js_css_rating() {
		$settings = $this->settings;

		do_action( 'cbxmcratingreview_enq_ratingform_js_css_before' );

		//enqueue styles
		//wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'awesome-notifications' );
		wp_enqueue_style( 'jquery-cbxmcratingreview-raty' );


		//enqueue script
		wp_enqueue_script( 'cbxmcratingreview-events' );
		wp_enqueue_script( 'jquery' );

		//wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-cbxmcratingreview-raty' );
		wp_enqueue_script( 'jquery-validate' );
		wp_enqueue_script( 'awesome-notifications' );


		do_action( 'cbxmcratingreview_enq_ratingform_js_css' );

		wp_enqueue_style( 'cbxmcratingreview-public' );
		wp_enqueue_style( 'cbxmcratingreview-ratingform' );

		wp_enqueue_script( 'cbxmcratingreview-public' );
		wp_enqueue_script( 'cbxmcratingreview-ratingform' );

		do_action( 'cbxmcratingreview_enq_ratingform_js_css_after' );
	}//end method enqueue_ratingform_js_css_rating

	/**
	 * Add all js and css needed for review edit form
	 */
	public function enqueue_ratingeditform_js_css_rating() {
		do_action( 'cbxmcratingreview_enq_ratingeditform_js_css_before' );


		//enqueue styles
		//wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'awesome-notifications' );
		wp_enqueue_style( 'jquery-cbxmcratingreview-raty' );


		//enqueue script
		wp_enqueue_script( 'cbxmcratingreview-events' );
		wp_enqueue_script( 'jquery' );

		//wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-cbxmcratingreview-raty' );
		wp_enqueue_script( 'jquery-validate' );
		wp_enqueue_script( 'awesome-notifications' );


		do_action( 'cbxmcratingreview_enq_ratingeditform_js_css' );

		//note: form always load the common public js and js

		wp_enqueue_style( 'cbxmcratingreview-public' );
		wp_enqueue_style( 'cbxmcratingreview-ratingform' );

		wp_enqueue_script( 'cbxmcratingreview-public' );
		wp_enqueue_script( 'cbxmcratingreview-ratingeditform' );

		do_action( 'cbxmcratingreview_enq_ratingeditform_js_css_after' );
	}//end method enqueue_ratingeditform_js_css_rating


	/**
	 * Ajax handler for post reviews load more
	 */
	public function post_more_reviews_ajax_load() {
		check_ajax_referer( 'cbxmcratingreview', 'security' );

		$form_id  = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;
		$post_id  = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$per_page = isset( $_POST['perpage'] ) ? intval( $_POST['perpage'] ) : 0;
		$page     = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
		$order_by = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'id';
		$order    = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : 'DESC';
		//$status  = isset( $_POST['status'] ) ? esc_attr( $_POST['status'] ) : ''; //this should be 1 for current regular implementation
		$score = isset( $_POST['score'] ) ? sanitize_text_field( wp_unslash( $_POST['score'] ) ) : '';

		$load_more = isset( $_POST['load_more'] ) ? intval( $_POST['load_more'] ) : 0;
		//$show_filter   = isset( $_POST['show_filter'] ) ? intval( $_POST['show_filter'] ) : 0;

		//filter must be set false
		$output = CBXMCRatingReviewHelper::postReviewsRender( $form_id, $post_id, $per_page, $page, 1, $score, $order_by, $order, $load_more );

		echo wp_json_encode( $output );

		wp_die();
	}//end method post_more_reviews_ajax_load

	/**
	 * Ajax handler for post reviews load more
	 */
	public function post_filter_reviews_ajax_load() {
		check_ajax_referer( 'cbxmcratingreview', 'security' );

		$form_id  = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;
		$post_id  = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$per_page = isset( $_POST['perpage'] ) ? intval( $_POST['perpage'] ) : 0;
		$page     = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
		$order_by = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'id';
		$order    = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : 'DESC';
		$score    = isset( $_POST['score'] ) ? sanitize_text_field( wp_unslash( $_POST['score'] ) ) : '';

		//filter must be set false

		$output_list = CBXMCRatingReviewHelper::postReviewsRender( $form_id, $post_id, $per_page, $page, 1, $score, $order_by, $order, 0 );

		$total_count   = cbxmcratingreview_totalPostReviewsCount( $form_id, $post_id, 1, $score );
		$maximum_pages = ceil( $total_count / $per_page );

		$show_readmore = ( $maximum_pages > 1 ) ? 1 : 0;

		$output = [
			'list_html' => $output_list,
			'orderby'   => $order_by,
			'order'     => $order,
			'score'     => $score,
			'load_more' => $show_readmore,
			'maxpage'   => $maximum_pages,
			'total'     => $total_count,
		];

		echo wp_json_encode( $output );

		wp_die();
	}//end method post_more_reviews_ajax_load

	/**
	 * Review rating entry via ajax
	 */
	public function review_rating_frontend_submit() {
		check_ajax_referer( 'cbxmcratingreview', 'security' );

		$settings = $this->settings;

		$show_headline    = absint( $settings->get_field( 'show_headline', 'cbxmcratingreview_common_config', 1 ) );
		$show_comment     = absint( $settings->get_field( 'show_comment', 'cbxmcratingreview_common_config', 1 ) );
		$require_headline = absint( $settings->get_field( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
		$require_comment  = absint( $settings->get_field( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );


		$default_status = absint( $settings->get_field( 'default_status', 'cbxmcratingreview_common_config', 1 ) );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$submit_data = isset( $_REQUEST['cbxmcratingreview_ratingForm'] ) ? wp_unslash( $_REQUEST['cbxmcratingreview_ratingForm'] ) : [];


		$validation_errors = $success_data = $return_response = $response_data_arr = [];
		$ok_to_process     = 0;
		$success_msg_class = $success_msg_info = '';


		$user_id = intval( get_current_user_id() );
		if ( is_user_logged_in() ) {
			$post_id = isset( $submit_data['post_id'] ) ? absint( $submit_data['post_id'] ) : 0;
			$form_id = isset( $submit_data['form_id'] ) ? absint( $submit_data['form_id'] ) : 0;

			//get the form setting
			$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


			$enable_question  = isset( $form['enable_question'] ) ? absint( $form['enable_question'] ) : 0;
			$custom_criterias = isset( $form['custom_criteria'] ) ? $form['custom_criteria'] : [];
			$custom_questions = isset( $form['custom_question'] ) ? $form['custom_question'] : [];

			$rating_scores      = isset( $submit_data['ratings'] ) ? $submit_data['ratings'] : [];
			$rating_score_total = 0;
			$rating_score_count = 0;


			$review_headline = isset( $submit_data['headline'] ) ? sanitize_text_field( wp_unslash( $submit_data['headline'] ) ) : '';
			$review_comment  = isset( $submit_data['comment'] ) ? wp_kses( wp_unslash( $submit_data['comment'] ), CBXMCRatingReviewHelper::allowedHtmlTags() ) : '';

			$questions_store = [];
			$ratings_stars   = [];


			if ( $post_id <= 0 ) {
				$validation_errors['top_errors']['post']['post_id_wrong'] = esc_html__( 'Sorry! Invalid post. Please check and try again.', 'cbxmcratingreview' );
			}

			//rating validation
			if ( is_array( $rating_scores ) && sizeof( $rating_scores ) > 0 ) {
				$rating_score_count = sizeof( $rating_scores );

				foreach ( $custom_criterias as $criteria_index => $custom_criteria ) {
					//$enabled     = isset( $custom_criteria['enabled'] ) ? intval( $custom_criteria['enabled'] ) : 0;
					$criteria_id = isset( $custom_criteria['criteria_id'] ) ? absint( $custom_criteria['criteria_id'] ) : intval( $criteria_index );
					/* translators: %d: Criteria ID  */
					$label = isset( $custom_criteria['label'] ) ? esc_attr( $custom_criteria['label'] ) : sprintf( esc_html__( 'Untitled criteria - %d', 'cbxmcratingreview' ), $criteria_id );

					$stars_formatted = is_array( $custom_criteria['stars_formatted'] ) ? $custom_criteria['stars_formatted'] : [];
					$stars_length    = isset( $stars_formatted['length'] ) ? absint( $stars_formatted['length'] ) : 0;
					$stars_hints     = isset( $stars_formatted['stars'] ) ? $stars_formatted['stars'] : [];


					if ( isset( $rating_scores[ $criteria_id ] ) ) {

						$rating_score     = $rating_scores[ $criteria_id ];
						$score_percentage = ( $stars_length != 0 ) ? ( $rating_score * 100 ) / $stars_length : 0;
						$score_standard   = ( $score_percentage != 0 ) ? ( ( $score_percentage * 5 ) / 100 ) : 0;
						$score_round      = ceil( $rating_score );
						$round_percentage = ( $stars_length != 0 ) ? ( $score_round * 100 ) / $stars_length : 0;

						//let's find the star from the score !
						//$star_id = array_keys( CBXMCRatingReviewHelper::getNthItemFromArr( $stars_hints, $score_round, 1, true ) )[0]; // we are so confident


						$ratings_stars[ $criteria_id ] = [
							//'star_id'          => $star_id,
							'stars_length'     => $stars_length,
							'score'            => $rating_score,
							'score_percentage' => $score_percentage,
							'score_standard'   => number_format( $score_standard, 2 ), //score in 5
							'score_round'      => $score_round,
							'round_percentage' => $round_percentage
						];


						$rating_score_total += floatval( $score_percentage );

						if ( $rating_score <= 0 || $rating_score > $stars_length ) {
							/* translators: %s: Criteria Label  */
							$validation_errors['cbxmcratingreview_rating_score'][ 'rating_score_wrong_' . $criteria_id ] = sprintf( __( 'Sorry! Invalid rating score for criteria <strong>%s</strong>. Please check and try again.', 'cbxmcratingreview' ), $label );
						}
					} elseif ( ! isset( $rating_scores[ $criteria_id ] ) ) {
						//todo: allow without rating ! , future thought
						/* translators: %s: Criteria Label  */
						$validation_errors['cbxmcratingreview_rating_score'][ 'rating_score_wrong_' . $criteria_id ] = sprintf( __( 'Sorry! Invalid rating score for criteria <strong>%s</strong>. Please check and try again.', 'cbxmcratingreview' ), $label );
					}


				}//end for each criteria
			} else {
				//error checking if review only submit approved
				$validation_errors['cbxmcratingreview_rating_score']['rating_score_wrong'] = esc_html__( 'Sorry! Invalid rating score or no rating selected. Please check and try again.', 'cbxmcratingreview' );
			}//end rating validation


			//questions validations
			$questions = isset( $submit_data['questions'] ) ? $submit_data['questions'] : [];

			//if question enabled for this form and question submitted
			if ( $enable_question && is_array( $questions ) && sizeof( $questions ) ) {
				//for each form questions
				foreach ( $custom_questions as $question_index => $question ) {
					$field_type = isset( $question['type'] ) ? $question['type'] : '';
					$enabled    = isset( $question['enabled'] ) ? absint( $question['enabled'] ) : 0;
					/* translators: %d: Question ID  */
					$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );

					if ( $field_type != '' && $enabled ) {
						$required = isset( $question['required'] ) ? absint( $question['required'] ) : 0;
						$multiple = isset( $question['multiple'] ) ? absint( $question['multiple'] ) : 0;
						//if question answered
						if ( isset( $questions[ $question_index ] ) ) {
							$answer = $questions[ $question_index ];

							if ( $field_type == 'text' || $field_type == 'textarea' || $field_type == 'number' || ( $field_type == 'select' && $multiple == 0 ) ) {
								if ( $required && $answer == '' ) {
									/* translators: %s: Question Title  */
									$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( wp_kses( __( 'Sorry! Question <strong>%s</strong> is blank but required. Please check and try again.', 'cbxmcratingreview' ), [ 'strong' => [] ] ), $title );
								}
							} elseif ( $field_type == 'select' && $multiple ) {
								if ( $required && sizeof( array_filter( $answer, [
										'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
										'arrayFilterRemoveEmpty'
									] ) ) == 0 ) {
									/* translators: %s: Question Title  */
									$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( wp_kses( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), [ 'strong' => [] ] ), $title );
								}
							} elseif ( $field_type == 'checkbox' ) {

							} elseif ( $field_type == 'multicheckbox' ) {

								if ( $required && sizeof( array_filter( $answer, [
										'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
										'arrayFilterRemoveEmpty'
									] ) ) == 0 ) {
									/* translators: %s: Question Title  */
									$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( wp_kses( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), [ 'strong' => [] ] ), $title );
								}
							}


							//now store the answer
							if ( is_array( $answer ) ) {
								$answer = maybe_serialize( array_filter( $answer, [
									'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
									'arrayFilterRemoveEmpty'
								] ) );
							}
							$questions_store[ $question_index ] = $answer;
						} elseif ( $required ) {
							//required but not submitted
							/* translators: %s: Question Title  */
							$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( wp_kses( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), [ 'strong' => [] ] ), $title );
						}
					}


				}
			}//end if question answer submitted
			//end question validation


			if ( $show_headline && $require_headline && $review_headline == '' ) {
				$validation_errors['cbxmcratingreview_review_headline']['review_headline_empty'] = esc_html__( 'Please provide title', 'cbxmcratingreview' );
			}

			if ( $show_comment && $require_comment && $review_comment == '' ) {
				$validation_errors['cbxmcratingreview_review_comment']['review_comment_empty'] = esc_html__( 'Please provide review', 'cbxmcratingreview' );
			}

		} else {
			$validation_errors['top_errors']['user']['user_guest'] = esc_html__( 'You aren\'t currently logged in. Please login to rate.', 'cbxmcratingreview' );
		}

		$validation_errors = apply_filters( 'cbxmcratingreview_review_entry_validation_errors', $validation_errors, $form_id, $post_id, $submit_data );

		if ( sizeof( $validation_errors ) > 0 ) {

		} else {

			$default_status = apply_filters( 'cbxmcratingreview_review_review_default_status', $default_status, $form_id, $post_id );

			$ok_to_process = 1;

			global $wpdb;

			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';

			$user_rated_before = cbxmcratingreview_isPostRatedByUser( $form_id, $post_id, $user_id );

			$multiple_review = false;
			$multiple_review = apply_filters( 'cbxmcratingreview_review_review_repeat', $multiple_review, $form_id, $post_id, $user_id );


			$log_insert_status = false;

			if ( ( $user_rated_before == false ) || $multiple_review ) {
				$attachment = [];

				$attachment = apply_filters( 'cbxmcratingreview_review_entry_attachment', $attachment, $form_id, $post_id, $submit_data );

				$extra_params = [];
				$extra_params = apply_filters( 'cbxmcratingreview_review_entry_extraparams', $extra_params, $form_id, $post_id, $submit_data );


				$rating_avg_percentage = $rating_score_total / $rating_score_count; //in 100%

				$rating_avg_score = ( $rating_avg_percentage != 0 ) ? ( $rating_avg_percentage * 5 ) / 100 : 0; //scale within 5

				$ratings = [
					'ratings_stars'  => $ratings_stars,
					'avg_percentage' => $rating_avg_percentage,
					'avg_score'      => $rating_avg_score
				];

				// insert rating log
				$data = [
					'post_id'      => $post_id,
					'form_id'      => $form_id,
					'post_type'    => get_post_type( $post_id ),
					'user_id'      => $user_id,
					'score'        => number_format( $rating_avg_score, 2 ),
					'headline'     => $review_headline,
					'comment'      => $review_comment,
					'extraparams'  => maybe_serialize( $extra_params ),
					'attachment'   => maybe_serialize( $attachment ),
					'status'       => $default_status,
					'date_created' => current_time( 'mysql' ),
					'ratings'      => maybe_serialize( $ratings ),
					'questions'    => maybe_serialize( $questions_store )
				];

				$data = apply_filters( 'cbxmcratingreview_review_entry_data', $data, $form_id, $post_id, $submit_data );

				$data_format = [
					'%d', // post_id
					'%d', // form_id
					'%s', // post_type
					'%d', // user_id
					'%f', // score
					'%s', // headline
					'%s', // comment
					'%s', // extraparams
					'%s', // attachment
					'%s', // status
					'%s', // date_created
					'%s', // ratings
					'%s', // questions
				];

				$data_format = apply_filters( 'cbxmcratingreview_review_entry_data_format', $data_format, $form_id, $post_id, $submit_data );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$log_insert_status = $wpdb->insert(
					$table_rating_log,
					$data,
					$data_format
				);

				if ( $log_insert_status != false ) {
					$new_review_id = $wpdb->insert_id;

					do_action( 'cbxmcratingreview_review_entry_just_success', $form_id, $post_id, $submit_data, $new_review_id );

					$success_msg_class = 'success';
					/* translators: %s: avg score  */
					$success_msg_info = sprintf( esc_html__( 'Thank you for your rating and review. You rated avg %s', 'cbxmcratingreview' ), number_format_i18n( $rating_avg_score, 2 ) . '/' . number_format_i18n( 5 ) );
					if ( $default_status != 1 ) {
						$success_msg_info .= '<br/>';
						$success_msg_info .= esc_html__( 'It will be published after admin approval and you will be notified.', 'cbxmcratingreview' );

						$success_msg_info = apply_filters( 'cbxmcratingreview_review_entry_success_info', $success_msg_info, 'success' );
					}

					$review_info = cbxmcratingreview_singleReview( $new_review_id );

					$response_data_arr['post_id']         = $review_info['post_id'];
					$response_data_arr['form_id']         = $review_info['form_id'];
					$response_data_arr['user_id']         = $review_info['user_id'];
					$response_data_arr['rating_id']       = $review_info['id'];
					$response_data_arr['rating_score']    = $review_info['score'] . '/5';
					$response_data_arr['headline']        = $review_info['headline'];
					$response_data_arr['comment']         = $review_info['comment'];
					$response_data_arr['date_created']    = CBXMCRatingReviewHelper::dateReadableFormat( $review_info['date_created'] );
					$response_data_arr['new_review_info'] = $review_info;

					//return last review with the response data
					if ( $default_status == 1 ) {
						$response_data_arr['review_html'] = '<li id="cbxmcratingreview_review_list_item_' . intval( $new_review_id ) . '" class="' . apply_filters( 'cbxmcratingreview_review_list_item_class', 'cbxmcratingreview_review_list_item' ) . '">' . cbxmcratingreview_singleReviewRender( $review_info ) . '</li>';
					}


					//if published then calculate avg
					if ( intval( $default_status ) == 1 ) {
						do_action( 'cbxmcratingreview_review_publish', $review_info );
					}

					$response_data_arr = apply_filters( 'cbxmcratingreview_review_entry_response_data', $response_data_arr, $form_id, $post_id, $submit_data, $review_info );

					do_action( 'cbxmcratingreview_review_entry_success', $form_id, $post_id, $submit_data, $review_info );

				}
			} else {
				$success_msg_class = 'warning';
				$success_msg_info  = esc_html__( 'Sorry! You already rated this or multiple reviews is not possible.', 'cbxmcratingreview' );
				$success_msg_info  = apply_filters( 'cbxmcratingreview_review_entry_success_info', $success_msg_info, 'failed' );
			}


			$success_data['responsedata'] = $response_data_arr;
			$success_data['class']        = $success_msg_class;
			$success_data['msg']          = $success_msg_info;
		}//end review submit validation

		$return_response['ok_to_process'] = $ok_to_process;
		$return_response['success']       = $success_data;
		$return_response['error']         = $validation_errors;

		echo wp_json_encode( $return_response );
		wp_die();
	}//end method review_rating_submit

	/**
	 * if review edited in publish mode
	 *
	 * @param $new_status
	 * @param $review_info
	 * @param $review_info_old
	 */
	public function cbxmcratingreview_review_update_without_status_adjust_postavg( $new_status, $review_info, $review_info_old ) {
		//if status is edited in puhlished mode
		if ( $new_status == 1 ) {
			CBXMCRatingReviewHelper::editPostwAvg( $new_status, $review_info, $review_info_old );
		}
	}//end method cbxmcratingreview_review_update_without_status_adjust_postavg

	/**
	 * Review Toolbar render
	 */
	public function cbxmcratingreview_single_review_toolbar( $post_review ) {
		echo cbxmcratingreview_reviewToolbarRender( $post_review ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end method cbxmcratingreview_single_review_toolbar

	public function cbxmcratingreview_single_review_delete_button( $post_review ) {
		$settings = $this->settings;

		$allow_review_delete = $settings->get_field( 'allow_review_delete', 'cbxmcratingreview_common_config', 1 );
		if ( is_user_logged_in() && absint( $allow_review_delete ) == 1 ) {
			$current_user_id = get_current_user_id();
			$review_user_id  = absint( $post_review['user_id'] );

			if ( $current_user_id == $review_user_id ) {
				echo cbxmcratingreview_reviewDeleteButtonRender( $post_review ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}//end method cbxmcratingreview_single_review_delete_button

	/**
	 * Ajax review delete
	 */
	public function review_delete_ajax() {
		check_ajax_referer( 'cbxmcratingreview', 'security' );
		$settings = $this->settings;

		$allow_review_delete = $settings->get_field( 'allow_review_delete', 'cbxmcratingreview_common_config', 1 );

		$output            = [];
		$output['success'] = 0;


		$review_id = isset( $_POST['review_id'] ) ? absint( $_POST['review_id'] ) : 0;

		if ( absint( $allow_review_delete ) == 0 ) {
			$output['message'] = esc_html__( 'Review delete is not possible. Please contact site authority.', 'cbxmcratingreview' );
		} elseif ( $review_id == 0 ) {
			$output['message'] = esc_html__( 'Review id is invalid', 'cbxmcratingreview' );
		} elseif ( ! is_user_logged_in() ) {
			$output['message'] = esc_html__( 'You are not logged in and you don\'t own the review. Area you cheating?', 'cbxmcratingreview' );
		} else {
			//now let's try to delete the message
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;


			$review_info = cbxmcratingreview_singleReview( $review_id );

			global $wpdb;
			$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';
			do_action( 'cbxmcratingreview_review_delete_before', $review_info );

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared , WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
			$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxmcratingreview_review WHERE id=%d AND user_id=%d", $review_id, $user_id ) );

			if ( $delete_status !== false ) {
				do_action( 'cbxmcratingreview_review_delete_after', $review_info );

				$output['success'] = 1;
				$output['message'] = esc_html__( 'Review deleted successfully!', 'cbxmcratingreview' );
			} else {
				$output['message'] = esc_html__( 'Review deleted failed!', 'cbxmcratingreview' );
			}
		}

		echo wp_json_encode( $output );
		wp_die();
	}//end method review_delete_ajax

	/**
	 * Auto integration
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function the_content_auto_integration( $content ) {
		if ( is_admin() ) {
			return $content;
		}

		if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) {
			return $content;
		}

		global $post;

		$forms = CBXMCRatingReviewHelper::getRatingForms(); //return all fields include extra fields merging to regular fields
		//for each form
		foreach ( $forms as $form ) {
			$form_id = intval( $form['id'] ) ? intval( $form['id'] ) : 0;
			if ( $form_id === 0 ) {
				return $content;
			}

			//if form disable return
			$status = isset( $form['status'] ) ? intval( $form['status'] ) : 0;
			if ( $status === 0 ) {
				return $content;
			}


			$post_id   = absint( $post->ID );
			$post_type = $post->post_type;

			//check if post type supported
			$post_types = isset( $form['post_types'] ) ? $form['post_types'] : [];
			if ( ! in_array( $post_type, $post_types ) ) {
				return $content;
			}

			$auto_integration = isset( $form['enable_auto_integration'] ) ? absint( $form['enable_auto_integration'] ) : 0;
			$show_on_single   = isset( $form['show_on_single'] ) ? absint( $form['show_on_single'] ) : 0;
			$show_on_home     = isset( $form['show_on_home'] ) ? absint( $form['show_on_home'] ) : 0;
			$show_on_archive  = isset( $form['show_on_arcv'] ) ? absint( $form['show_on_arcv'] ) : 0;

			//if auto integration enabled
			if ( $auto_integration ) {

				//check if post type supported for auto integration
				$post_types_auto = isset( $form['post_types_auto'] ) ? $form['post_types_auto'] : [];

				if ( ! in_array( $post_type, $post_types_auto ) ) {
					return $content;
				}

				if ( is_home() && $show_on_home ) {
					$extra_html_avg = '';
					if ( function_exists( 'cbxmcratingreview_postAvgRatingRender' ) ) {
						$extra_html_avg = cbxmcratingreview_postAvgRatingRender( $form_id, $post_id );
					}

					return $extra_html_avg . $content;
				} elseif ( is_archive() && $show_on_archive ) {
					$extra_html_avg = '';
					if ( function_exists( 'cbxmcratingreview_postAvgRatingRender' ) ) {
						$extra_html_avg = cbxmcratingreview_postAvgRatingRender( $form_id, $post_id );
					}

					return $extra_html_avg . $content;

				} elseif ( is_singular() && $show_on_single ) {
					$extra_html_avg  = '';
					$extra_html_form = '';
					$extra_html_list = '';

					$post_reviews_count = CBXMCRatingReviewHelper::totalPostReviewsCount( $form_id, $post_id, 1, '' );

					if ( $post_reviews_count == 0 ) {
						if ( function_exists( 'cbxmcratingreview_postAvgRatingRender' ) ) {
							$extra_html_avg .= cbxmcratingreview_postAvgRatingRender( $form_id, $post_id, true, true, false );
						}
					} else {
						if ( function_exists( 'cbxmcratingreview_postAvgDetailsRatingRender' ) ) {
							$extra_html_avg .= cbxmcratingreview_postAvgDetailsRatingRender( $form_id, $post_id, true, true, true, true );
						}
					}


					if ( function_exists( 'cbxmcratingreview_reviewformRender' ) ) {
						$extra_html_form = cbxmcratingreview_reviewformRender( $form_id, $post_id );
					}

					$extra_html_list .= do_shortcode( '[cbxmcratingreview_postreviews form_id="' . $form_id . '" post_id="' . $post_id . '"]' );

					return $extra_html_avg . $content . $extra_html_form . $extra_html_list;
				}

			}//if end auto integration
		}//end for each form

		return $content;
	}//end method the_content_auto_integration
}//end CBXMCRatingReviewPublic