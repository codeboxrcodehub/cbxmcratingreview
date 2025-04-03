<?php

namespace CBX\MCRatingReview;

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewAdminHelper;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use CBX\MCRatingReview\Models\RatingReviewLog;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/admin
 * @author     Sabuj Kundu <sabuj@codeboxr.com>
 */
class CBXMCRatingReviewAdmin {

	/**
	 * The setting of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string $version The current version of this plugin.
	 */
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
	 * Admin rating form listing view
	 */
	public static function display_admin_form_listing_page() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$template_name = cbxmcratingreview_get_template_html( 'admin/admin-rating-review-rating-forms.php', [] );

		echo( $template_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end method display_admin_form_listing_page

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return  array
	 * @since 2.0.0
	 */
	public static function plugin_action_links( $links ) {
		$action_links = [
			'settings' => '<a style="color: #6648fe !important; font-weight: bold;" href="' . admin_url( 'admin.php?page=cbxmcratingreview-settings' ) . '" aria-label="' . esc_attr__( 'View settings',
					'cbxmcratingreview' ) . '">' . esc_html__( 'Settings', 'cbxmcratingreview' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}//end method get_settings_sections

	public function setting_init() {
		//set the settings
		$this->settings->set_sections( $this->get_settings_sections() );
		$this->settings->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings->admin_init();
	}//end method get_settings_sections

	/**
	 * Global Setting Sections and titles
	 *
	 * @return array
	 */
	public static function get_settings_sections() {
		$settings_sections = [
			[
				'id'    => 'cbxmcratingreview_common_config',
				'title' => esc_html__( 'General', 'cbxmcratingreview' )
			],
			[
				'id'    => 'cbxmcratingreview_email_tpl',
				'title' => esc_html__( 'Email Template', 'cbxmcratingreview' )
			],
			[
				'id'    => 'cbxmcratingreview_tools',
				'title' => esc_html__( 'Pages & Tools', 'cbxmcratingreview' )
			]
		];

		return apply_filters( 'cbxmcratingreview_setting_sections', $settings_sections );
	}//end method get_settings_fields

	/**
	 * Global Setting Fields
	 *
	 * @return array
	 */
	public function get_settings_fields() {

		$settings = $this->settings;

		$reviews_status_options  = CBXMCRatingReviewHelper::ReviewStatusOptions();
		$reviews_positive_scores = CBXMCRatingReviewHelper::ReviewPositiveScores();

		$rating_forms = CBXMCRatingReviewHelper::getRatingFormsList();

		//$table_names = CBXMCRatingReviewHelper::getAllDBTablesList();
		//$table_keys  = CBXMCRatingReviewHelper::getAllDBTablesKeyList();

		$gust_login_forms = CBXMCRatingReviewHelper::guest_login_forms();

		$cbxmcratingreview_common_config_fields = [
			'cbxmcratingreview_common_config_heading' => [
				'name'    => 'cbxmcratingreview_common_config_heading',
				'label'   => esc_html__( 'General Settings', 'cbxmcratingreview' ),
				'type'    => 'heading',
				'default' => '',
			],
			'default_form'                            => [
				'name'        => 'default_form',
				'label'       => esc_html__( 'Default Rating Form', 'cbxmcratingreview' ),
				'desc'        => esc_html__( 'Please choose default rating form. ', 'cbxmcratingreview' ),
				'type'        => 'select',
				'default'     => 0,
				'options'     => $rating_forms,
				'placeholder' => esc_html__( 'Select Form', 'cbxmcratingreview' )
			],
			'allow_review_delete'                     => [
				'name'    => 'allow_review_delete',
				'label'   => esc_html__( 'Allow Review Delete', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Allow user delete review from frontend', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' ),
				]
			],
			'half_rating'                             => [
				'name'    => 'half_rating',
				'label'   => esc_html__( 'Allow Half Rating', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'If half rating enabled, user can rate .5, 1.5, 2.5, 3.5, 4.5 with regular 1, 2,3,4,5 values.',
					'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => 0,
				'options' => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' ),
				]
			],
			'default_status'                          => [
				'name'    => 'default_status',
				'label'   => esc_html__( 'Default Review Status', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'What will be status when a new review is written?', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => 1,
				'options' => $reviews_status_options
			],
			'show_headline'                           => [
				'name'    => 'show_headline',
				'label'   => esc_html__( 'Show Headline', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Show/hide review headline in rating form', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' ),
				]
			],
			'require_headline'                        => [
				'name'    => 'require_headline',
				'label'   => esc_html__( 'Headline Required', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Is headline mandatory to write a review?', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' ),
				]
			],
			'show_comment'                            => [
				'name'    => 'show_comment',
				'label'   => esc_html__( 'Show Comment', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Show/hide comment in rating form', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' ),
				]
			],
			'require_comment'                         => [
				'name'    => 'require_comment',
				'label'   => esc_html__( 'Comment Required', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Is comment mandatory to write a review?', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' ),
				]
			],

			'enable_positive_critical' => [
				'name'    => 'enable_positive_critical',
				'label'   => esc_html__( 'Enable Positive/Critical Score', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Enable positivive or critial score functionality', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' ),
				]
			],
			'positive_score'           => [
				'name'    => 'positive_score',
				'label'   => esc_html__( 'Positve Review Score value', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Select minimum score value for a positive review', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => 4,
				'options' => $reviews_positive_scores
			],
			'default_per_page'         => [
				'name'    => 'default_per_page',
				'label'   => esc_html__( 'Reviews Per Page', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Default number of reviews per page in pagination', 'cbxmcratingreview' ),
				'type'    => 'text',
				'default' => '10'
			],
			'show_review_filter'       => [
				'name'    => 'show_review_filter',
				'label'   => esc_html__( 'Show Review Filter', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Show filter box in review listing', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' ),
				]
			],

			/*'show_login_form'                      => [
				'name'              => 'show_login_form',
				'label'             => esc_html__( 'Show login form for guest user', 'cbxmcratingreview' ),
				'desc'              => esc_html__( 'If select yes then show the login form for the guest users on the new job creating',
					'cbxmcratingreview' ),
				'type'              => 'radio',
				'default'           => 'yes',
				'options'           => [
					'yes' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'no'  => esc_html__( 'No', 'cbxmcratingreview' ),
				],
				'sanitize_callback' => 'sanitize_text_field'
			],*/
			'guest_login_form'         => [
				'name'    => 'guest_login_form',
				'label'   => esc_html__( 'Guest User Login Form', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Default guest user is shown wordpress core login form. Pro addon helps to integrate 3rd party plugins like woocommerce, restrict content pro etc.', 'cbxmcratingreview' ),
				'type'    => 'select',
				'default' => 'wordpress',
				'options' => $gust_login_forms
			],
			'guest_show_register'      => [
				'name'    => 'guest_show_register',
				'label'   => esc_html__( 'Show Register link to guest', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Show register link to guest, depends on if registration is enabled in wordpress core',
					'cbxmcratingreview' ),
				'type'    => 'radio',
				'default' => 1,
				'options' => [
					1 => esc_html__( 'Yes', 'cbxmcratingreview' ),
					0 => esc_html__( 'No', 'cbxmcratingreview' ),
				],
			],
		];


		$cbxmcratingreview_global_email_fields = [
			'cbxmcratingreview_email_tpl_heading' => [
				'name'    => 'cbxmcratingreview_email_tpl_heading',
				'label'   => esc_html__( 'Email Template', 'cbxmcratingreview' ),
				'type'    => 'heading',
				'default' => '',
			],
			'headerimage'                         => [
				'name'    => 'headerimage',
				'label'   => esc_html__( 'Header Image', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'Url To email you want to show as email header.Upload Image by media uploader.',
					'cbxmcratingreview' ),
				'type'    => 'file',
				'default' => ''
			],
			'footertext'                          => [
				'name'    => 'footertext',
				'label'   => esc_html__( 'Footer Text', 'cbxmcratingreview' ),
				'desc'    => wp_kses( __( 'The text to appear at the email footer. Syntax available - <code>{site_title}</code>',
					'cbxmcratingreview' ), [ 'code' => [] ] ),
				'type'    => 'wysiwyg',
				'default' => '{site_title}'
			],
			'basecolor'                           => [
				'name'    => 'basecolor',
				'label'   => esc_html__( 'Base Color', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'The base color of the email.', 'cbxmcratingreview' ),
				'type'    => 'color',
				'default' => '#557da1'
			],
			'backgroundcolor'                     => [
				'name'    => 'backgroundcolor',
				'label'   => esc_html__( 'Background Color', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'The background color of the email.', 'cbxmcratingreview' ),
				'type'    => 'color',
				'default' => '#f5f5f5'
			],
			'bodybackgroundcolor'                 => [
				'name'    => 'bodybackgroundcolor',
				'label'   => esc_html__( 'Body Background Color', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'The background colour of the main body of email.', 'cbxmcratingreview' ),
				'type'    => 'color',
				'default' => '#fdfdfd'
			],
			'bodytextcolor'                       => [
				'name'    => 'bodytextcolor',
				'label'   => esc_html__( 'Body Text Color', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'The body text colour of the main body of email.', 'cbxmcratingreview' ),
				'type'    => 'color',
				'default' => '#505050'
			],
			'footertextcolor'                     => [
				'name'    => 'footertextcolor',
				'label'   => esc_html__( 'Footer Text Color', 'cbxmcratingreview' ),
				'desc'    => esc_html__( 'The footer text colour of the footer of email.', 'cbxmcratingreview' ),
				'type'    => 'color',
				'default' => '#3c3c3c',
			],
		];

		$single_review_view_id = absint( $settings->get_field( 'single_review_view_id', 'cbxmcratingreview_tools',
			0 ) );
		//$single_review_edit_id   = intval( $settings->get_field( 'single_review_edit_id', 'cbxmcratingreview_tools', 0 ) );
		$review_userdashboard_id = absint( $settings->get_field( 'review_userdashboard_id', 'cbxmcratingreview_tools',
			0 ) );

		$single_review_view_shortcode_text = '<strong>' . esc_html__( 'Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.',
				'cbxmcratingreview' ) . '</strong>';
		$single_review_edit_shortcode_text = '<strong>' . esc_html__( 'Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.',
				'cbxmcratingreview' ) . '</strong>';
		$user_dashboard_shortcode_text     = '<strong>' . esc_html__( 'Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.',
				'cbxmcratingreview' ) . '</strong>';

		if ( $single_review_view_id > 0 ) {
			$content_post = get_post( $single_review_view_id );
			$content      = $content_post->post_content;
			if ( has_shortcode( $content, 'cbxmcratingreview_singlereview' ) ) {
				/* translators: %s: review link */
				$single_review_view_shortcode_text = '<strong>' . esc_html__( 'Shortcode detected on the selected page.', 'cbxmcratingreview' ) . '</strong>';
			}
		}

		if ( $review_userdashboard_id > 0 ) {
			$content_post = get_post( $review_userdashboard_id );
			$content      = $content_post->post_content;

			if ( has_shortcode( $content, 'cbxmcratingreview_userdashboard' ) ) {
				/* translators: %s: review user dashboard link */
				$user_dashboard_shortcode_text = '<strong>' . esc_html__( 'Shortcode detected on the selected page.', 'cbxmcratingreview' ) . '</strong>';
			}
		}


		$cbxmcratingreview_tools_fields = [
			'cbxmcratingreview_tools_heading' => [
				'name'    => 'cbxmcratingreview_tools_heading',
				'label'   => esc_html__( 'Pages & Tools', 'cbxmcratingreview' ),
				'type'    => 'heading',
				'default' => '',
			],
			'single_review_view_id'           => [
				'name'    => 'single_review_view_id',
				'label'   => esc_html__( 'Frontend Single Review View Page', 'cbxmcratingreview' ),
				'desc'    => wp_kses( __( 'Select page which will show the single review dynamically. That page must have the shortcode <code>[cbxmcratingreview_singlereview]</code>.',
						'cbxmcratingreview' ), [ 'code' => [] ] ) . $single_review_view_shortcode_text,
				'type'    => 'page',
				'default' => '',
				'options' => CBXMCRatingReviewHelper::get_pages( true )
			],
			'review_userdashboard_id'         => [
				'name'    => 'review_userdashboard_id',
				'label'   => esc_html__( 'Frontend User Dashboard', 'cbxmcratingreview' ),
				'desc'    => wp_kses( __( 'Select page which will show the the logged in user\'s dashboard to manage rating and reviews. That page must have the shortcode <code>[cbxmcratingreview_userdashboard]</code>.',
						'cbxmcratingreview' ), [ 'code' => [] ] ) . $user_dashboard_shortcode_text,
				'type'    => 'page',
				'default' => '',
				'options' => CBXMCRatingReviewHelper::get_pages( true )
			],
			'delete_global_config'            => [
				'name'    => 'delete_global_config',
				'label'   => esc_html__( 'On Uninstall delete plugin data', 'cbxmcratingreview' ),
				'desc'    => '<p>' . esc_html__( 'Delete Global Config data and custom table created by this plugin on uninstall.', 'cbxmcratingreview' ) . '</p>',
				'type'    => 'radio',
				'options' => [
					'yes' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'no'  => esc_html__( 'No', 'cbxmcratingreview' ),
				],
				'default' => 'no'
			]
		];

		$settings_builtin_fields =
			apply_filters( 'cbxmcratingreview_setting_fields', [
				'cbxmcratingreview_common_config' => apply_filters( 'cbxmcratingreview_common_config_fields',
					$cbxmcratingreview_common_config_fields ),
				'cbxmcratingreview_email_tpl'     => apply_filters( 'cbxmcratingreview_email_tpl',
					$cbxmcratingreview_global_email_fields ),
				'cbxmcratingreview_tools'         => apply_filters( 'cbxmcratingreview_tools_fields',
					$cbxmcratingreview_tools_fields )
			] );


		$settings_fields = []; //final setting array that will be passed to different filters

		$sections = $this->get_settings_sections();

		foreach ( $sections as $section ) {
			if ( ! isset( $settings_builtin_fields[ $section['id'] ] ) ) {
				$settings_builtin_fields[ $section['id'] ] = [];
			}
		}


		foreach ( $sections as $section ) {
			$settings_fields[ $section['id'] ] = $settings_builtin_fields[ $section['id'] ];
		}


		$settings_fields = apply_filters( 'cbxmcratingreview_setting_fields_final',
			$settings_fields ); //final filter if need

		return $settings_fields;
	}//end method admin_pages

	/**
	 * Show Admin Pages
	 */
	public function admin_pages() {
		global $submenu;

		add_menu_page(
			esc_html__( 'CBX Multi Criteria Rating & Review: Log Manager', 'cbxmcratingreview' ),
			esc_html__( 'Rating & Review', 'cbxmcratingreview' ),
			'cbxmcratingreview_dashboard_manage',
			'cbxmcratingreview-dashboard',
			[ $this, 'display_admin_dashboard_page' ],
			'dashicons-star-half'
		);

		//review listing page
		add_submenu_page(
			'cbxmcratingreview-dashboard',
			esc_html__( 'CBX Multi Criteria Rating & Review: Log Manager', 'cbxmcratingreview' ),
			esc_html__( 'Review Manager', 'cbxmcratingreview' ),
			'cbxmcratingreview_log_manage',
			'cbxmcratingreviewreview-list',
			[ $this, 'display_admin_review_listing_page' ], 1
		);


		//rating avg listing pageadmin_pages
		add_submenu_page(
			'cbxmcratingreview-dashboard',
			esc_html__( 'CBX Multi Criteria Rating & Review: Average Log Manager', 'cbxmcratingreview' ),
			esc_html__( 'Average Ratings', 'cbxmcratingreview' ),
			'cbxmcratingreview_log_manage',
			'cbxmcratingreviewrating-avg-list',
			[ $this, 'display_admin_rating_avg_listing_page' ], 2
		);

		add_submenu_page(
			'cbxmcratingreview-dashboard',
			esc_html__( 'CBX Multi Criteria Rating & Review: Rating Form Listing', 'cbxmcratingreview' ),
			esc_html__( 'Rating Forms', 'cbxmcratingreview' ),
			'cbxmcratingreview_form_manage',
			'cbxmcratingreview-form',
			[ $this, 'display_admin_form_listing_page' ], 3
		);

		//settings, emails, tools
		add_submenu_page(
			'cbxmcratingreview-dashboard',
			esc_html__( 'CBX Multi Criteria Rating & Review: Setting', 'cbxmcratingreview' ),
			esc_html__( 'Settings', 'cbxmcratingreview' ),
			'cbxmcratingreview_settings_manage',
			'cbxmcratingreview-settings',
			[ $this, 'display_plugin_admin_settings' ], 7
		);

		add_submenu_page( 'cbxmcratingreview-dashboard',
			esc_html__( 'CBX Multi Criteria Rating & Review: Email Manager', 'cbxmcratingreview' ),
			esc_html__( 'Emails', 'cbxmcratingreview' ),
			'cbxmcratingreview_settings_manage',
			'cbxmcratingreview-emails',
			[ $this, 'admin_menu_display_emails' ], 8
		);

		// Tools submenu add
		add_submenu_page(
			'cbxmcratingreview-dashboard',
			esc_html__( 'CBX Multi Criteria Rating & Review: Tools', 'cbxmcratingreview' ),
			esc_html__( 'Tools', 'cbxmcratingreview' ),
			'cbxmcratingreview_settings_manage',
			'cbxmcratingreview-tools',
			[ $this, 'display_tools_submenu_page' ], 9
		);
		//end settings, emails, tools

		//add help & support page for this plugin
		add_submenu_page(
			'cbxmcratingreview-dashboard',
			esc_html__( 'Helps & Updates', 'cbxmcratingreview' ),
			esc_html__( 'Helps & Updates', 'cbxmcratingreview' ),
			'cbxmcratingreview_settings_manage',
			'cbxmcratingreview-help-support',
			[ $this, 'display_plugin_help_support' ], 10
		);

		if ( isset( $submenu['cbxmcratingreview-dashboard'][0][0] ) ) {
			$submenu['cbxmcratingreview-dashboard'][0][0] = esc_html__( 'Dashboard', 'cbxmcratingreview' );
		}

		do_action( 'cbxmcratingreview_admin_pages', $this );
	}//end method display_admin_dashboard_page

	/**
	 * Show admin dashboard
	 *
	 * @return void
	 */
	public function display_admin_dashboard_page(): void {
		$settings = $this->settings;
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo cbxmcratingreview_get_template_html( 'admin/dashboard-global.php', [ 'settings' => $settings ] );
	}//end method display_plugin_admin_settings

	/**
	 * Display plugin setting page
	 */
	public function display_plugin_admin_settings() {
		global $wpdb;
		//$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . CBXMCRATINGREVIEW_PLUGIN_NAME . '.php' );
		//$plugin_data     = get_plugin_data( plugin_dir_path( __DIR__ ) . '/../' . $plugin_basename );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo cbxmcratingreview_get_template_html( 'admin/settings.php', [
			//'plugin_data' => $plugin_data,
			'ref'      => $this,
			'settings' => $this->settings
		] );
	}// end method display_plugin_help_support

	/**
	 * Display help & support
	 * @global type $wpdb
	 */
	public function display_plugin_help_support() {
		global $wpdb;
		echo cbxmcratingreview_get_template_html( 'admin/support.php' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end method display_tools_submenu_page

	/**
	 * Show application tools admin
	 *
	 * @return void
	 */
	public function display_tools_submenu_page() {
		echo cbxmcratingreview_get_template_html( 'admin/tools.php' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end method admin_menu_display_emails

/**
	 * Loads emails menu template
	 *
	 * @since 2.0.0
	 */
	public function admin_menu_display_emails() {
		$settings = $this->settings;

		$mail_helper = cbxmcratingreview_mailer();
		$emails      = $mail_helper->emails;

		$template_data = [ 'settings' => $settings, 'emails' => $emails, 'edit' => 0 ];

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['edit'] ) && $_REQUEST['edit'] != '' ) {
			$email_id              = sanitize_text_field( wp_unslash( $_REQUEST['edit'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$template_data['edit'] = 1;
			$template_data['id']   = $email_id;
		}

		echo cbxmcratingreview_get_template_html( 'admin/email_manager.php', $template_data );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end method display_admin_review_listing_page

/**
	 * Admin review listing view
	 */
	public function display_admin_review_listing_page() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$template_name = cbxmcratingreview_get_template_html( 'admin/admin-rating-review-review-logs.php' );

		echo( $template_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end method display_admin_rating_avg_listing_page

/**
	 * Admin review listing view
	 */
	public function display_admin_rating_avg_listing_page() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo cbxmcratingreview_get_template_html( 'admin/admin-rating-review-rating-avg-logs.php' );
	}//end method enqueue_styles

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

		$ratingforms_css_dep = [];

		$vendors_url_part = CBXMCRATINGREVIEW_ROOT_URL . 'assets/vendors/';
		$css_url_part     = CBXMCRATINGREVIEW_ROOT_URL . 'assets/css/';


		do_action( 'cbxmcratingreview_reg_admin_styles_before' );

		wp_register_style( 'sweetalert2', plugin_dir_url( __FILE__ ) . '../assets/js/sweetalert2/sweetalert2.css', [],
			$this->version, 'all' );


		wp_register_style( 'select2', $vendors_url_part . 'select2/select2.min.css', [], $this->version );
		wp_register_style( 'pickr', $vendors_url_part . 'pickr/classic.min.css', [], $this->version );
		wp_register_style( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/style.css', [],
			$this->version );


		$ratingforms_css_dep[] = 'sweetalert2';
		$ratingforms_css_dep[] = 'wp-color-picker';

		wp_register_style( 'cbxmcratingreview-builder', $css_url_part . 'cbxmcratingreview-builder.css', [],
			$this->version, 'all' );

		wp_register_style( 'cbxmcratingreview-admin',
			plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-admin.css', [], $this->version, 'all' );


		wp_register_style( 'cbxmcratingreview-setting',
			plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-settings.css', [
				'select2',
				'pickr',
				'awesome-notifications'
			], $this->version, 'all' );

		wp_register_style( 'cbxmcratingreview-email-manager', plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-email-manager.css', [], $this->version, 'all' );
		wp_register_style( 'cbxmcratingreview-dashboard', $css_url_part . 'cbxmcratingreview-dashboard.css', [], $this->version, 'all' );

		do_action( 'cbxmcratingreview_reg_admin_styles' );

		//except setting, other main plugin's views

		if ( $current_page == 'cbxmcratingreviewreview-list' || $current_page == 'cbxmcratingreviewrating-avg-list' ) {
			wp_enqueue_style( 'cbxmcratingreview-admin' );
			wp_enqueue_style( 'cbxmcratingreview-builder' );
		}

		//only for setting
		if ( $current_page == 'cbxmcratingreview-settings' ) {
			wp_enqueue_style( 'cbxmcratingreview-setting' );
			wp_enqueue_style( 'cbxmcratingreview-admin' );
		}

		//add css for form listing and edit page
		if ( $current_page == 'cbxmcratingreview-form' ) {
			wp_enqueue_style( 'cbxmcratingreview-admin' );
			wp_enqueue_style( 'cbxmcratingreview-builder' );
		}

		if ( $current_page == 'cbxmcratingreview-tools' || $current_page == 'cbxmcratingreview-help-support' ) {
			wp_enqueue_style( 'cbxmcratingreview-admin' );
		}

		if ( $current_page == 'cbxmcratingreview-emails' ) {
			wp_enqueue_style( 'cbxmcratingreview-email-manager' );
		}

		if ( $current_page == 'cbxmcratingreview-dashboard' ) {
			wp_enqueue_style( 'cbxmcratingreview-admin' );
			wp_enqueue_style( 'cbxmcratingreview-dashboard' );
		}

		do_action( 'cbxmcratingreview_reg_admin_styles' );

	}//end method enqueue_scripts


	//on publish review calculate avg

/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

		$vendors_url_part   = CBXMCRATINGREVIEW_ROOT_URL . 'assets/vendors/';
		$ver                = $this->version;
		$js_url_part_vanila = CBXMCRATINGREVIEW_ROOT_URL . 'assets/js/vanila/';

		$settings = $this->settings;


		$require_headline = intval( $settings->get_field( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
		$require_comment  = intval( $settings->get_field( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );
		$half_rating      = intval( $settings->get_field( 'half_rating', 'cbxmcratingreview_common_config', 0 ) );


		do_action( 'cbxmcratingreview_reg_admin_scripts_before' );


		//only for setting page
		if ( $current_page == 'cbxmcratingreview-settings' ) {

			wp_register_script( 'select2', $vendors_url_part . 'select2/select2.min.js', [ 'jquery' ], $ver,
				true );
			wp_register_script( 'pickr', $vendors_url_part . 'pickr/pickr.min.js', [], $ver, true );
			wp_register_script( 'awesome-notifications', $vendors_url_part . 'awesome-notifications/script.js', [],
				$ver, true );


			wp_register_script( 'cbxmcratingreview-settings', $js_url_part_vanila . 'cbxmcratingreview-settings.js', [
				'jquery',
				'select2',
				'pickr',
				'awesome-notifications'
			], $ver, true );

			$translation_placeholder = apply_filters(
				'cbxmcratingreview_setting_js_vars',
				[
					'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
					'ajax_fail'                => esc_html__( 'Request failed, please reload the page.',
						'cbxmcratingreview' ),
					'nonce'                    => wp_create_nonce( 'cbxmcratingreviewnonce' ),
					'is_user_logged_in'        => is_user_logged_in() ? 1 : 0,
					'please_select'            => esc_html__( 'Please Select', 'cbxmcratingreview' ),
					'search'                   => esc_html__( 'Search...', 'cbxmcratingreview' ),
					'upload_title'             => esc_html__( 'Window Title', 'cbxmcratingreview' ),
					'search_placeholder'       => esc_html__( 'Search here', 'cbxmcratingreview' ),
					/*'teeny_setting'            => [
						'teeny'         => true,
						'media_buttons' => true,
						'editor_class'  => '',
						'textarea_rows' => 5,
						'quicktags'     => false,
						'menubar'       => false,
					],*/
					'copycmds'                 => [
						'copy'       => esc_html__( 'Copy', 'cbxmcratingreview' ),
						'copied'     => esc_html__( 'Copied', 'cbxmcratingreview' ),
						'copy_tip'   => esc_html__( 'Click to copy', 'cbxmcratingreview' ),
						'copied_tip' => esc_html__( 'Copied to clipboard', 'cbxmcratingreview' ),
					],
					'confirm_msg'              => esc_html__( 'Are you sure to remove this step?',
						'cbxmcratingreview' ),
					'confirm_msg_all'          => esc_html__( 'Are you sure to remove all steps?',
						'cbxmcratingreview' ),
					'confirm_yes'              => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'confirm_no'               => esc_html__( 'No', 'cbxmcratingreview' ),
					'are_you_sure_global'      => esc_html__( 'Are you sure?', 'cbxmcratingreview' ),
					'are_you_sure_delete_desc' => esc_html__( 'Once you delete, it\'s gone forever. You can not revert it back.',
						'cbxmcratingreview' ),
					'pickr_i18n'               => [
						// Strings visible in the UI
						'ui:dialog'       => esc_html__( 'color picker dialog', 'cbxmcratingreview' ),
						'btn:toggle'      => esc_html__( 'toggle color picker dialog', 'cbxmcratingreview' ),
						'btn:swatch'      => esc_html__( 'color swatch', 'cbxmcratingreview' ),
						'btn:last-color'  => esc_html__( 'use previous color', 'cbxmcratingreview' ),
						'btn:save'        => esc_html__( 'Save', 'cbxmcratingreview' ),
						'btn:cancel'      => esc_html__( 'Cancel', 'cbxmcratingreview' ),
						'btn:clear'       => esc_html__( 'Clear', 'cbxmcratingreview' ),

						// Strings used for aria-labels
						'aria:btn:save'   => esc_html__( 'save and close', 'cbxmcratingreview' ),
						'aria:btn:cancel' => esc_html__( 'cancel and close', 'cbxmcratingreview' ),
						'aria:btn:clear'  => esc_html__( 'clear and close', 'cbxmcratingreview' ),
						'aria:input'      => esc_html__( 'color input field', 'cbxmcratingreview' ),
						'aria:palette'    => esc_html__( 'color selection area', 'cbxmcratingreview' ),
						'aria:hue'        => esc_html__( 'hue selection slider', 'cbxmcratingreview' ),
						'aria:opacity'    => esc_html__( 'selection slider', 'cbxmcratingreview' ),
					],
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
					'global_setting_link_html' => '<a href="' . admin_url( 'admin.php?page=cbxmcratingreview-settings' ) . '"  class="button outline primary pull-right">' . esc_html__( 'Global Settings',
							'cbxmcratingreview' ) . '</a>',
					'lang'                     => get_user_locale(),
				]
			);

			wp_localize_script( 'cbxmcratingreview-settings', 'cbxmcratingreview_setting',
				apply_filters( 'cbxmcratingreview_setting_js_vars', $translation_placeholder ) );


			wp_enqueue_script( 'jquery' );
			wp_enqueue_media();

			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'pickr' );
			wp_enqueue_script( 'awesome-notifications' );

			wp_enqueue_script( 'cbxmcratingreview-settings' );

			do_action( 'cbxmcratingreview_enq_admin_setting_js_after' );
		}

		$current_user      = wp_get_current_user();
		$blog_id           = is_multisite() ? get_current_blog_id() : null;
		$js_url_part_build = CBXMCRATINGREVIEW_ROOT_URL . 'assets/js/build/';

		if ( $current_page == 'cbxmcratingreview-form' ) {

			do_action( 'cbxmcratingreview_enq_admin_forms_js_before' );

			$js_translations = CBXMCRatingReviewAdminHelper::form_builder_js_translation( $current_user,
				$blog_id );


			$js_translations['require_headline'] = $require_headline;
			$js_translations['require_comment']  = $require_comment;


			if ( defined( 'CBXMCRATINGREVIEW_DEV_MODE' ) && CBXMCRATINGREVIEW_DEV_MODE == true ) {
				//for development version
				wp_register_script( 'cbxmcratingreview_form_vue_dev',
					'http://localhost:8880/assets/vuejs/apps/admin/cbxmcratingreviewform.js', [], $ver, true );
				wp_localize_script( 'cbxmcratingreview_form_vue_dev', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_form_vue_dev' );
			} else {
				// for production
				wp_register_script( 'cbxmcratingreview_form_vue_main', $js_url_part_build . 'cbxmcratingreviewform.js',
					[],
					$ver, true );
				wp_localize_script( 'cbxmcratingreview_form_vue_main', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_form_vue_main' );
			}

			do_action( 'cbxmcratingreview_enq_admin_forms_js_after' );
		}

		if ( $current_page == 'cbxmcratingreviewreview-list' ) {

			$js_translations = CBXMCRatingReviewHelper::cbxmcratingreview_log_builder_js_translation( $current_user,
				$blog_id );

			$js_translations['half_rating'] = $half_rating;

			if ( defined( 'CBXMCRATINGREVIEW_DEV_MODE' ) && CBXMCRATINGREVIEW_DEV_MODE == true ) {
				//for development version
				wp_register_script( 'cbxmcratingreview_log_vue_dev',
					'http://localhost:8880/assets/vuejs/apps/admin/cbxmcratingreviewlog.js', [], $ver, true );
				wp_localize_script( 'cbxmcratingreview_log_vue_dev', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_log_vue_dev' );
			} else {
				// for production
				wp_register_script( 'cbxmcratingreview_log_vue_main', $js_url_part_build . 'cbxmcratingreviewlog.js',
					[], $ver, true );
				wp_localize_script( 'cbxmcratingreview_log_vue_main', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_log_vue_main' );
			}

		}

		if ( $current_page == 'cbxmcratingreviewrating-avg-list' ) {

			$js_translations = CBXMCRatingReviewHelper::cbxmcratingreview_log_builder_js_translation( $current_user,
				$blog_id );

			if ( defined( 'CBXMCRATINGREVIEW_DEV_MODE' ) && CBXMCRATINGREVIEW_DEV_MODE == true ) {
				//for development version
				wp_register_script( 'cbxmcratingreview_log_vue_dev',
					'http://localhost:8880/assets/vuejs/apps/admin/cbxmcratingreviewavglog.js', [], $ver, true );
				wp_localize_script( 'cbxmcratingreview_log_vue_dev', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_log_vue_dev' );
			} else {
				// for production
				wp_register_script( 'cbxmcratingreview_log_vue_main', $js_url_part_build . 'cbxmcratingreviewavglog.js',
					[], $ver, true );
				wp_localize_script( 'cbxmcratingreview_log_vue_main', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_log_vue_main' );
			}

		}

		if ( $current_page == 'cbxmcratingreview-tools' ) {
			$js_translations = CBXMCRatingReviewAdminHelper::cbxmcratingreview_tools_js_translation( $current_user,
				$blog_id );

			$js_translations['migration_files']      = CBXMCRatingReviewAdminHelper::migration_files();
			$js_translations['migration_files_left'] = CBXMCRatingReviewAdminHelper::migration_files_left();

			if ( defined( 'CBXMCRATINGREVIEW_DEV_MODE' ) && CBXMCRATINGREVIEW_DEV_MODE == true ) {
				//for development version
				wp_register_script( 'cbxmcratingreview_tools_vue_dev',
					'http://localhost:8880/assets/vuejs/apps/admin/cbxmcratingreviewtools.js', [], $ver, true );
				wp_localize_script( 'cbxmcratingreview_tools_vue_dev', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_tools_vue_dev' );
			} else {
				// for production
				wp_register_script( 'cbxmcratingreview_tools_vue_main', $js_url_part_build . 'cbxmcratingreviewtools.js',
					[], $ver, true );
				wp_localize_script( 'cbxmcratingreview_tools_vue_main', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_tools_vue_main' );
			}
		}

		if ( $current_page == 'cbxmcratingreview-dashboard' ) {

			$js_translations = CBXMCRatingReviewAdminHelper::cbxmcratingreview_dashboard_js_translation( $current_user,
				$blog_id );

			if ( defined( 'CBXMCRATINGREVIEW_DEV_MODE' ) && CBXMCRATINGREVIEW_DEV_MODE == true ) {
				//for development version
				wp_register_script( 'cbxmcratingreview_dashboard_vue_dev',
					'http://localhost:8880/assets/vuejs/apps/admin/cbxmcratingreviewdashboard.js', [], $ver, true );
				wp_localize_script( 'cbxmcratingreview_dashboard_vue_dev', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_dashboard_vue_dev' );
			} else {
				// for production
				wp_register_script( 'cbxmcratingreview_dashboard_vue_main', $js_url_part_build . 'cbxmcratingreviewdashboard.js',
					[], $ver, true );
				wp_localize_script( 'cbxmcratingreview_dashboard_vue_main', 'cbxmcratingreview_vue_var', $js_translations );
				wp_enqueue_script( 'cbxmcratingreview_dashboard_vue_main' );
			}
		}

		do_action( 'cbxmcratingreview_reg_admin_scripts' );
	}//end method review_publish_adjust_avg

	//on unpublish review adjust avg

public function review_publish_adjust_avg( $review_info ) {
		//calculate avg
		CBXMCRatingReviewHelper::calculatePostAvg( $review_info );
	}//end method review_unpublish_adjust_avg

	public function review_unpublish_adjust_avg( $review_info ) {
		CBXMCRatingReviewHelper::adjustPostwAvg( $review_info );
	}//end method review_delete_after

	/**
	 * Do some extra cleanup on after review delete
	 *
	 * @param $review_info
	 */
	public function review_delete_after( $review_info ) {
		//adjust avg
		CBXMCRatingReviewHelper::adjustPostwAvg( $review_info );

	}//end method form_delete_after

	/**
	 * After rating form delete
	 *
	 * @param $form_info
	 */
	public function form_delete_after( $form_info ) {
		$form_id = isset( $form_info['id'] ) ? intval( $form_info['id'] ) : 0;

		if ( $form_id > 0 ) {
			RatingReviewLog::where( 'form_id', $form_id )->delete();
		}
	}//end method review_delete_after_delete_user

	/**
	 * On user delete delete reviews
	 *
	 * @param $user_id
	 */
	public function review_delete_after_delete_user( $user_id ) {
		$user_id = intval( $user_id );

		if ( $user_id > 0 ) {
			$forms = CBXMCRatingReviewHelper::getRatingFormsList();
			if ( is_array( $forms ) && sizeof( $forms ) > 0 ) {
				foreach ( $forms as $form_id => $form_name ) {

					//delete all reviews for this user
					RatingReviewLog::where( 'form_id', $form_id )->where( 'user_id', $user_id )->delete();
				}
			}
		}
	}//end method review_delete_after_delete_post_init

	/**
	 * Post delete hook init
	 */
	public function review_delete_after_delete_post_init() {
		add_action( 'delete_post', [ $this, 'review_delete_after_delete_post' ], 10 );
	}//end method review_delete_after_delete_post

	/**
	 * On post  delete delete reviews
	 *
	 * @param $post_id
	 */
	public function review_delete_after_delete_post( $post_id ) {
		$post_id = absint( $post_id );

		if ( $post_id > 0 ) {
			RatingReviewLog::where( 'post_id', $post_id )->delete();
		}
	}//end method save_email_setting

	/**
	 * Save email/notification setting
	 *
	 * @return void
	 */
	public function save_email_setting() {
		if ( isset( $_REQUEST['cbxmcratingreview_email_edit'] ) ) {
			$email_id = isset( $_POST['email_id'] ) ? sanitize_text_field( wp_unslash( $_POST['email_id'] ) ) : '';
			$nonce    = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( $email_id != '' ) {
				if ( ! wp_verify_nonce( $nonce, 'cbxmcratingreview_email_edit_' . $email_id ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					die( esc_html__( 'Security check failed!', 'cbxmcratingreview' ) );
				} else {
					// Do stuff here.
					$admin_url    = admin_url( 'admin.php?page=cbxmcratingreview-emails' );
					$redirect_url = add_query_arg( [ 'edit' => $email_id ], $admin_url );

					$mail_helper = cbxmcratingreview_mailer();
					$emails      = $mail_helper->emails;
					$email       = $emails[ $email_id ];
					$form_fields = $email->form_fields;
					$settings    = $email->settings;

					foreach ( $form_fields as $field_key => $form_field ) {
						if ( isset( $_POST[ $field_key ] ) ) {
							$type = $form_field['type'];
							if ( $type == 'checkbox' ) {
								$settings[ $field_key ] = sanitize_text_field( wp_unslash( $_POST[ $field_key ] ) );
							} elseif ( $type == 'textarea' ) {
								$settings[ $field_key ] = sanitize_textarea_field( wp_unslash( $_POST[ $field_key ] ) );
							} else {
								$settings[ $field_key ] = sanitize_text_field( wp_unslash( $_POST[ $field_key ] ) );
							}
						} else {
							$settings[ $field_key ] = $form_field['default'];
						}
					}

					$email_options = get_option( 'cbxmcratingreview_emails', [] );

					$email_options[ $email_id ] = $settings;
					update_option( 'cbxmcratingreview_emails', $email_options );

					wp_safe_redirect( $redirect_url );
					exit;
				}
			} else {
				die( esc_html__( 'Sorry, invalid email id', 'cbxmcratingreview' ) );
			}
		}
	}//end method custom_message_after_plugin_row_proaddon

	/**
	 * Show plugin update
	 *
	 * @param $plugin_file
	 * @param $plugin_data
	 *
	 * @return void
	 */
	public function custom_message_after_plugin_row_proaddon( $plugin_file, $plugin_data ) {
		if ( $plugin_file !== 'cbxmcratingreviewpro/cbxmcratingreviewpro.php' ) {
			return;
		}

		if ( defined( 'CBXMCRATINGREVIEWPRO_PLUGIN_NAME' ) ) {
			return;
		}


		$pro_addon_version   = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';
		$pro_current_version = '2.0.0';


		if ( $pro_addon_version != '' && version_compare( $pro_addon_version, $pro_current_version, '<' ) ) {
			// Custom message to display

			$plugin_manual_update = 'https://codeboxr.com/manual-update-pro-addon/';

			/* translators:translators: %s: plugin setting url for licence */
			$custom_message = wp_kses( sprintf( __( '<strong>Note:</strong> CBX Multi Criteria Rating & Review Pro Addon is custom plugin. This plugin can not be auto update from dashboard/plugin manager. For manual update please check <a target="_blank" href="%1$s">documentation</a>. <strong style="color: red;">It seems this plugin\'s current version is older than %2$s. To get the latest pro addon features, this plugin needs to upgrade to %2$s or later.</strong>', 'cbxmcratingreview' ),
				esc_url( $plugin_manual_update ), $pro_current_version ), [
				'strong' => [ 'style' => [] ],
				'a'      => [
					'href'   => [],
					'target' => []
				]
			] );

			// Output a row with custom content
			echo '<tr class="plugin-update-tr">
            <td colspan="3" class="plugin-update colspanchange">
                <div class="notice notice-warning inline">
                    ' . wp_kses_post( $custom_message ) . '
                </div>
            </td>
          </tr>';
		}
	}//end plugin_upgrader_process_complete

	/**
	 * If we need to do something in upgrader process is completed
	 *
	 */
	public function plugin_upgrader_process_complete() {
		$saved_version = get_option( 'cbxmcratingreview_version' );

		if ( $saved_version === false || version_compare( $saved_version, CBXMCRATINGREVIEW_PLUGIN_VERSION, '<' ) ) {
			//load orm
			CBXMCRatingReviewHelper::load_orm();

			// Run the upgrade routine
			CBXMCRatingReviewHelper::migration_and_defaults();

			add_action( 'init', [ $this, 'plugin_upgrader_process_complete_partial' ] );

			//set upgrade notice in transient
			set_transient( 'cbxmcratingreview_upgraded_notice', 1 );

			// Update the saved version
			update_option( 'cbxmcratingreview_version', CBXMCRATINGREVIEW_PLUGIN_VERSION );

			//disable comment and mycred
		}
	}//end method plugin_upgrader_process_complete_partial

/**
	 * Run partial migration
	 *
	 * @return void
	 */
	public function plugin_upgrader_process_complete_partial() {
		CBXMCRatingReviewHelper::create_pages();
	}//end plugin_activate_upgrade_notices

	/**
	 * Show a notice to anyone who has just installed the plugin for the first time
	 * This notice shouldn't display to anyone who has just updated this plugin
	 */
	public function plugin_activate_upgrade_notices() {
		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxmcratingreview_activated_notice' ) ) {
			echo '<div style="border-left-color: #005ae0;" class="notice notice-success is-dismissible">';
			/* translators: %s: bookmark core plugin version */
			echo '<p>' . sprintf( wp_kses( __( 'Thanks for installing/deactivating <strong>CBX Multi Criteria Rating & Review</strong> V%s - Codeboxr Team', 'cbxmcratingreview' ), [ 'strong' => [] ] ), esc_attr( CBXMCRATINGREVIEW_PLUGIN_VERSION ) ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			/* translators: 1. Plugin setting url 2. Documentation link */
			echo '<p>' . sprintf( wp_kses( __( 'Check <a style="color:#005ae0 !important; font-weight: bold;" href="%1$s">Plugin Setting</a> | <a style="color:#005ae0 !important; font-weight: bold;" href="%2$s" target="_blank">Documentation</a>', 'cbxmcratingreview' ), [ 'a' => [ 'href'   => [],
			                                                                                                                                                                                                                                                                             'style'  => [],
			                                                                                                                                                                                                                                                                             'target' => []
				]
				] ), esc_url( admin_url( 'admin.php?page=cbxmcratingreview-settings' ) ),
					'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/' ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';

			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxmcratingreview_activated_notice' );

			//$this->pro_addon_compatibility_campaign();
		}

		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxmcratingreview_upgraded_notice' ) ) {
			echo '<div style="border-left-color: #005ae0;" class="notice notice-success is-dismissible">';
			/* translators: %s: bookmark core plugin version */
			echo '<p>' . sprintf( wp_kses( __( 'Thanks for upgrading <strong>CBX Multi Criteria Rating & Review</strong> V%s , enjoy the new features and bug fixes - Codeboxr Team', 'cbxmcratingreview' ), [ 'strong' => [] ] ), esc_attr( CBXMCRATINGREVIEW_PLUGIN_VERSION ) ) . '</p>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			/* translators: 1. Plugin setting url 2. Documentation link */
			echo '<p>' . sprintf( wp_kses( __( 'Check <a style="color:#005ae0 !important; font-weight: bold;" href="%1$s">Plugin Setting</a> | <a style="color:#005ae0 !important; font-weight: bold;" href="%2$s" target="_blank">Documentation</a>', 'cbxmcratingreview' ), [ 'a' => [ 'href'   => [],
			                                                                                                                                                                                                                                                                             'style'  => [],
			                                                                                                                                                                                                                                                                             'target' => []
				]
				] ), esc_url( admin_url( 'admin.php?page=cbxmcratingreview-settings' ) ), 'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/' ) . '</p>';
			echo '</div>';

			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxmcratingreview_upgraded_notice' );

			//$this->pro_addon_compatibility_campaign();
		}

		if ( get_transient( 'cbxmcratingreview_proaddon_deactivated' ) ) {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #6648fe !important;">';

			echo '<p>';
			esc_html_e( 'Current version of CBX Multi Criteria Rating & Review Pro Addon is not compatible with core CBX Multi Criteria Rating & Review. CBX Multi Criteria Rating & Review Pro Addon is forced deactivate.', 'cbxmcratingreview' );

			echo '</p>';
			echo '</div>';
			delete_transient( 'cbxmcratingreview_proaddon_deactivated' );
		}

		if ( get_transient( 'cbxmcratingreview_commentaddon_deactivated' ) ) {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #6648fe !important;">';

			echo '<p>';
			esc_html_e( 'Current version of CBX Multi Criteria Rating & Review Comment Addon is not compatible with core CBX Multi Criteria Rating & Review but it\'s been merged with pro addon. CBX Multi Criteria Rating & Review Comment Addon is forced deactivate.', 'cbxmcratingreview' );

			echo '</p>';
			echo '</div>';
			delete_transient( 'cbxmcratingreview_commentaddon_deactivated' );
		}

		if ( get_transient( 'cbxmcratingreview_mycredaddon_deactivated' ) ) {
			echo '<div class="notice notice-success is-dismissible" style="border-color: #6648fe !important;">';

			echo '<p>';
			esc_html_e( 'Current version of CBX Multi Criteria Rating & Review myCred Addon is not compatible with core CBX Multi Criteria Rating & Review but it\'s been merged with pro addon. CBX Multi Criteria Rating & Review myCred Addon is forced deactivate.', 'cbxmcratingreview' );

			echo '</p>';
			echo '</div>';
			delete_transient( 'cbxmcratingreview_mycredaddon_deactivated' );
		}
	}//end plugin_action_links

	/**
	 * Filters the array of row meta for each/specific plugin in the Plugins list table.
	 * Appends additional links below each/specific plugin on the plugins page.
	 *
	 * @access  public
	 *
	 * @param array $links_array An array of the plugin's metadata
	 * @param string $plugin_file_name Path to the plugin file
	 * @param array $plugin_data An array of plugin data
	 * @param string $status Status of the plugin
	 *
	 * @return  array       $links_array
	 * @since 2.0.0
	 */
	public function plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
		if ( strpos( $plugin_file_name, CBXMCRATINGREVIEW_BASE_NAME ) !== false ) {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$links_array[] = '<a target="_blank" style="color:#6044ea !important; font-weight: bold;" href="https://wordpress.org/support/plugin/cbxmcratingreview/" aria-label="' . esc_attr__( 'Free Support',
					'cbxmcratingreview' ) . '">' . esc_html__( 'Free Support', 'cbxmcratingreview' ) . '</a>';

			$links_array[] = '<a target="_blank" style="color:#6044ea !important; font-weight: bold;" href="https://wordpress.org/plugins/cbxmcratingreview/#reviews" aria-label="' . esc_attr__( 'Reviews',
					'cbxmcratingreview' ) . '">' . esc_html__( 'Reviews', 'cbxmcratingreview' ) . '</a>';


			$links_array[] = '<a target="_blank" style="color:#6044ea !important; font-weight: bold;" href="https://codeboxr.com/doc/cbxmcratingreview-doc/" aria-label="' . esc_attr__( 'Documentation',
					'cbxmcratingreview' ) . '">' . esc_html__( 'Documentation', 'cbxmcratingreview' ) . '</a>';

			if ( defined( 'CBXMCRATINGREVIEWPRO_PLUGIN_NAME' ) && in_array( 'cbxmcratingreviewpro/cbxmcratingreviewpro.php', apply_filters( 'active_plugins',
					get_option( 'active_plugins' ) ) ) ) {
				//pro addon active
			} else {
				$links_array[] = '<a target="_blank" style="color:#6044ea !important; font-weight: bold;" href="https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/" aria-label="' . esc_attr__( 'Try Pro Addon',
						'cbxmcratingreview' ) . '">' . esc_html__( 'Try Pro Addon', 'cbxmcratingreview' ) . '</a>';
			}
		}

		return $links_array;
	}//end plugin_row_meta

	/**
	 * Show notice about pro addon deactivation
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function check_pro_addon() {
		//pro addon
		cbxmcratingreview_check_version_and_deactivate_plugin( 'cbxmcratingreviewpro/cbxmcratingreviewpro.php', '2.0.0', 'cbxmcratingreview_proaddon_deactivated' );

		cbxmcratingreview_check_and_deactivate_plugin( 'cbxmcratingreviewcomment/cbxmcratingreviewcomment.php', 'cbxmcratingreview_commentaddon_deactivated' );
		cbxmcratingreview_check_and_deactivate_plugin( 'cbxmcratingreviewmycred/cbxmcratingreviewmycred.php', 'cbxmcratingreview_mycredaddon_deactivated' );
	}//end method check_pro_addon
}//end class CBXMCRatingReviewAdmin