<?php

namespace CBX\MCRatingReview\Helpers;


use CBX\MCRatingReview\MigrationManage;
use CBX\MCRatingReview\Models\RatingReviewLog;

/**
 * The helper functionality of the plugin admin sides
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
	<?php

class CBXMCRatingReviewAdminHelper {
	/**
	 * Add utm params to any url
	 *
	 * @param string $url
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function url_utmy( $url = '' ) {
		if ( $url == '' ) {
			return $url;
		}

		return add_query_arg( [
			'utm_source'   => 'plgsidebarinfo',
			'utm_medium'   => 'plgsidebar',
			'utm_campaign' => 'wpfreemium',
		], $url );
	}//end method url_utmy

	/**
	 * dashboard menu list
	 *
	 * @since 1.0.0
	 */
	public static function dashboard_menus() {
		$menus = [];

		if ( current_user_can( 'cbxmcratingreview_log_manage' ) ) {
			$menus['cbxmcratingreviewreview-list'] = [
				'url'        => admin_url( 'admin.php?page=cbxmcratingreviewreview-list' ),
				'title-attr' => esc_html__( 'Manage Log', 'cbxmcratingreview' ),
				'title'      => esc_html__( 'Log Manager', 'cbxmcratingreview' ),
			];
		}

		if ( current_user_can( 'cbxmcratingreview_form_manage' ) ) {
			$menus['cbxmcratingreviewreview-form'] = [
				'url'        => admin_url( 'admin.php?page=cbxmcratingreviewreview-form' ),
				'title-attr' => esc_html__( 'Manage Review Form', 'cbxmcratingreview' ),
				'title'      => esc_html__( 'Review Form Manager', 'cbxmcratingreview' ),
			];
		}

		if ( current_user_can( 'cbxmcratingreview_log_manage' ) ) {
			$menus['cbxmcratingreviewrating-avg-list'] = [
				'url'        => admin_url( 'admin.php?page=cbxmcratingreviewrating-avg-list' ),
				'title-attr' => esc_html__( 'Manage Average Log', 'cbxmcratingreview' ),
				'title'      => esc_html__( 'Average Log Manager', 'cbxmcratingreview' ),
			];
		}

		if ( current_user_can( 'cbxmcratingreview_settings_manage' ) ) {
			$menus['cbxmcratingreview-settings'] = [
				'url'        => admin_url( 'admin.php?page=cbxmcratingreview-settings' ),
				'title-attr' => esc_html__( 'Manage Settings', 'cbxmcratingreview' ),
				'title'      => esc_html__( 'Settings', 'cbxmcratingreview' ),
			];
		}

		return $menus;
	}// end function dashboard_menus

	/**
	 * form builder js translation list
	 *
	 * @param $current_user
	 * @param $blog_id
	 *
	 * @return mixed|void
	 */
	public static function form_builder_js_translation( $current_user, $blog_id ) {
		$common_js_translations = CBXMCRatingReviewHelper::common_js_translation( $current_user, $blog_id );

		$user_roles_no_guest   = CBXMCRatingReviewHelper::user_roles( true, false );
		$user_roles_with_guest = CBXMCRatingReviewHelper::user_roles( true, true );

		$form_default_criterias = CBXMCRatingReviewHelper::form_default_criterias();
		$form_default_questions = CBXMCRatingReviewHelper::form_default_questions();

		$form_default_fields = CBXMCRatingReviewHelper::form_default_fields();

		$form_js_translations = [
			'post_types'             => CBXMCRatingReviewHelper::post_types( true ),
			'user_roles_no_guest'    => $user_roles_no_guest,
			'user_roles_with_guest'  => $user_roles_with_guest,
			'form_default_criterias' => $form_default_criterias,
			'form_default_fields'    => $form_default_fields,
			'form_default_questions' => $form_default_questions,
			'question_field_types'   => CBXMCRatingReviewHelper::question_field_types(),
			'translations'           => [
				'post_types'                           => esc_html__( 'Post Types', 'cbxmcratingreview' ),
				'add_new'                              => esc_html__( 'Add New', 'cbxmcratingreview' ),
				'name'                                 => esc_html__( 'Name', 'cbxmcratingreview' ),
				'form'                                 => esc_html__( 'Form', 'cbxmcratingreview' ),
				'label'                                => esc_html__( 'Label', 'cbxmcratingreview' ),
				'value'                                => esc_html__( 'Value', 'cbxmcratingreview' ),
				'edit'                                 => esc_html__( 'Edit', 'cbxmcratingreview' ),
				'options'                              => esc_html__( 'Options', 'cbxmcratingreview' ),
				'forms'                                => esc_html__( 'Forms', 'cbxmcratingreview' ),
				'title'                                => esc_html__( 'Title', 'cbxmcratingreview' ),
				'subject'                              => esc_html__( 'Subject', 'cbxmcratingreview' ),
				'search_text'                          => esc_html__( 'Search', 'cbxmcratingreview' ),
				'update'                               => esc_html__( 'Update', 'cbxmcratingreview' ),
				'close'                                => esc_html__( 'Close', 'cbxmcratingreview' ),
				'type'                                 => esc_html__( 'Type', 'cbxmcratingreview' ),
				'date'                                 => esc_html__( 'Date', 'cbxmcratingreview' ),
				'form_manager'                         => htmlspecialchars_decode( esc_html__( 'Rating & Review : Rating Form Manager', 'cbxmcratingreview' ) ),
				'no_form_found'                        => esc_html__( 'No Form found', 'cbxmcratingreview' ),
				'general_fields'                       => esc_html__( 'General Fields', 'cbxmcratingreview' ),
				'custom_criteria'                      => esc_html__( 'Custom Criteria', 'cbxmcratingreview' ),
				'custom_criterias'                     => esc_html__( 'Custom Criterias', 'cbxmcratingreview' ),
				'custom_questions'                     => esc_html__( 'Custom Questions', 'cbxmcratingreview' ),
				'enabled'                              => esc_html__( 'Enabled', 'cbxmcratingreview' ),
				'disabled'                             => esc_html__( 'Disabled', 'cbxmcratingreview' ),
				'post_type_support'                    => esc_html__( 'Post Type Support', 'cbxmcratingreview' ),
				'who_can_give_rate'                    => htmlspecialchars_decode( esc_html__( 'Who Can give Rate & Review',
					'cbxmcratingreview' ) ),
				'who_can_view_rate'                    => htmlspecialchars_decode( esc_html__( 'Who Can View Rating & Review',
					'cbxmcratingreview' ) ),
				'enable_auto_integration'              => esc_html__( 'Enable Auto Integration', 'cbxmcratingreview' ),
				'auto_integration'                     => esc_html__( 'Auto Integration', 'cbxmcratingreview' ),
				'post_types_auto'                      => esc_html__( 'Auto Integration for Post Type',
					'cbxmcratingreview' ),
				'show_on_single'                       => esc_html__( 'Show on Single', 'cbxmcratingreview' ),
				'show_on_home'                         => esc_html__( 'Show on Home/Frontpage', 'cbxmcratingreview' ),
				'show_on_arcv'                         => esc_html__( 'Show on Archives', 'cbxmcratingreview' ),
				'enable_question'                      => esc_html__( 'Enable Question', 'cbxmcratingreview' ),
				'write_rating_form_name'               => esc_html__( 'Write rating form name', 'cbxmcratingreview' ),
				'enable_disable_the_form'              => esc_html__( 'Enable disable the form', 'cbxmcratingreview' ),
				'which_post_types_can_have_the_rating' => htmlspecialchars_decode( esc_html__( 'Which post types can have the rating & review features. Please make sure multiple form is not associated with same post type for best performance.',
					'cbxmcratingreview' ) ),
				'which_user_role_vote'                 => esc_html__( 'Which user role will have vote capability',
					'cbxmcratingreview' ),
				'which_user_role_view'                 => esc_html__( 'Which user role will have view capability',
					'cbxmcratingreview' ),
				'enable_disable_auto_integration'      => esc_html__( 'Enable/disable auto integration, ie, add average rating before post content in archive, in details article mode add average rating information before content, rating form & review listing after content',
					'cbxmcratingreview' ),
				'enable_which_post_types_will_have'    => esc_html__( 'Enable which post types will have auto integration features. Please note that selected post types should be within the post types selected for Post Type Support',
					'cbxmcratingreview' ),
				'enable_disable_for_single_article'    => esc_html__( 'Enable disable for single article(post, page or any custom post type), related with auto integration.',
					'cbxmcratingreview' ),
				'enable_disable_for_home'              => esc_html__( 'Enable disable for home/frontpage, related with auto integration.',
					'cbxmcratingreview' ),
				'enable_disable_for_archive_pages'     => esc_html__( 'Enable disable for archive pages, related with auto integration.',
					'cbxmcratingreview' ),
				'enable_question_with_rating'          => esc_html__( 'Enable Question with Rating',
					'cbxmcratingreview' ),
				'questions'                            => esc_html__( 'Questions', 'cbxmcratingreview' ),
				'questions_are_optional'               => esc_html__( 'Questions are optional. Each question can be set enabled or disabled, required. Multiple types of questions including Radio, Checkbox, Text, Textarea and more.',
					'cbxmcratingreview' ),
				'question_title'                       => esc_html__( 'Questions Title',
					'cbxmcratingreview' ),
				'controls'                             => esc_html__( 'Controls', 'cbxmcratingreview' ),
				'field_type'                           => esc_html__( 'Field Type', 'cbxmcratingreview' ),
				'field_preview'                        => esc_html__( 'Field Preview',
					'cbxmcratingreview' ),
				'sample_question_title'                => esc_html__( 'Sample Question Title', 'cbxmcratingreview' ),
				'text'                                 => esc_html__( 'Text', 'cbxmcratingreview' ),
				'yes'                                  => esc_html__( 'Yes', 'cbxmcratingreview' ),
				'no'                                   => esc_html__( 'No', 'cbxmcratingreview' ),
				'required_question'                    => esc_html__( 'Required Question ?', 'cbxmcratingreview' ),
				'show_question'                        => esc_html__( 'Show Question', 'cbxmcratingreview' ),
				'placeholder_text'                     => esc_html__( 'Placeholder Text', 'cbxmcratingreview' ),
				'info_unlimited_question'              => esc_html__( 'Info: Unlimited Question available in pro version.',
					'cbxmcratingreview' ),
				'actions'                              => esc_html__( 'Actions', 'cbxmcratingreview' ),
				'question'                             => esc_html__( 'Question', 'cbxmcratingreview' ),
			]
		];

		$js_translations = array_merge_recursive( $common_js_translations, $form_js_translations );

		return apply_filters( 'cbxmcratingreview_form_js_translation', $js_translations );
	} //end method form_builder_js_translation

	/**
	 * Get the user roles for voting purpose
	 *
	 * @param bool $plain
	 * @param bool $include_guest
	 * @param array $ignore
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function user_roles( $plain = true, $include_guest = false, $ignore = [] ) {
		global $wp_roles;

		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );
		}

		$userRoles = [];
		if ( $plain ) {
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				if ( in_array( $role, $ignore ) ) {
					continue;
				}
				$userRoles[ $role ] = $roleInfo['name'];
			}
			if ( $include_guest ) {
				$userRoles['guest'] = esc_html__( 'Guest', 'cbxmcratingreview' );
			}
		} else {
			//optgroup
			$userRoles_r = [];
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				if ( in_array( $role, $ignore ) ) {
					continue;
				}
				$userRoles_r[ $role ] = $roleInfo['name'];
			}

			$userRoles = [
				'Registered' => $userRoles_r,
			];

			if ( $include_guest ) {
				$userRoles['Anonymous'] = [
					'guest' => esc_html__( 'Guest', 'cbxmcratingreview' )
				];
			}
		}

		return apply_filters( 'cbxmcratingreview_user_roles', $userRoles, $plain, $include_guest );
	} //end method cbxmcratingreview_tools_js_translation

	/**
	 * Tools js translation list
	 *
	 * @param $current_user
	 * @param $blog_id
	 *
	 * @return mixed|void
	 */
	public static function cbxmcratingreview_tools_js_translation( $current_user, $blog_id ) {

		$common_js_translations = CBXMCRatingReviewHelper::common_js_translation( $current_user, $blog_id );

		$tools_js_translations = [
			'translations' => [
				'tools' => [
					'following_option_values' => esc_html__( 'Following option values created by this plugin(including addon) from WordPress core option table', 'cbxmcratingreview' ),
					'check_all'               => esc_html__( 'Check All', 'cbxmcratingreview' ),
					'uncheck_all'             => esc_html__( 'Uncheck All', 'cbxmcratingreview' ),
					'option_name'             => esc_html__( 'Option Name', 'cbxmcratingreview' ),
					'option_id'               => esc_html__( 'Option ID', 'cbxmcratingreview' ),
					'reset_data'              => esc_html__( 'Reset Data', 'cbxmcratingreview' ),
					'please_select_one'       => esc_html__( 'Please select at least one option', 'cbxmcratingreview' ),
					'reset_option_data'       => esc_html__( 'Reset option data', 'cbxmcratingreview' ),
					'show_hide'               => esc_html__( 'Show/Hide', 'cbxmcratingreview' ),
					'done'                    => esc_html__( 'Done', 'cbxmcratingreview' ),
					'need_migrate'            => esc_html__( 'Need to migrate', 'cbxmcratingreview' ),
					'migration_files'         => esc_html__( 'Migration Files', 'cbxmcratingreview' ),
					'run_migration'           => esc_html__( 'Run Migration', 'cbxmcratingreview' ),
					'migration_file_name'     => esc_html__( 'Migration File Name', 'cbxmcratingreview' ),
					'status'                  => esc_html__( 'Status', 'cbxmcratingreview' ),
					'heading'                 => esc_html__( 'Tools Manager', 'cbxmcratingreview' ),
				],
			],
			'option_array' => CBXMCRatingReviewHelper::getAllOptionNames(),
		];

		$js_translations = array_merge_recursive( $common_js_translations, $tools_js_translations );

		return apply_filters( 'cbxmcratingreview_tools_js_translation', $js_translations );
	} //end of method job_dashboard_js_translation

	/**
	 * translation for cbxmcratingreview type
	 *
	 * @param $current_user
	 * @param $blog_id
	 *
	 * @return mixed|void
	 * @since 1.0.0
	 */
	public static function cbxmcratingreview_dashboard_js_translation( $current_user, $blog_id ) {
		$common_js_translations = CBXMCRatingReviewHelper::common_js_translation( $current_user, $blog_id );

		$tools_js_translations = [
			'translations'    => [
				'heading'                            => htmlspecialchars_decode( esc_html__( 'Rating & Review : Dashboard', 'cbxmcratingreview' ) ),
				'dashboard_overview'                 => esc_html__( 'Dashboard Overview', 'cbxmcratingreview' ),
				'quick_information'                  => esc_html__( 'Quick information of important components', 'cbxmcratingreview' ),
				'form'                               => esc_html__( 'Form', 'cbxmcratingreview' ),
				'forms'                              => esc_html__( 'Forms', 'cbxmcratingreview' ),
				'forms_created'                      => esc_html__( 'form(s) created', 'cbxmcratingreview' ),
				'review'                             => esc_html__( 'Review', 'cbxmcratingreview' ),
				'reviews'                            => esc_html__( 'Reviews', 'cbxmcratingreview' ),
				'review_submitted'                   => esc_html__( 'reviews submitted', 'cbxmcratingreview' ),
				'reviewed_post'                      => esc_html__( 'Post Reviewed ', 'cbxmcratingreview' ),
				'post_reviewed'                      => esc_html__( 'post(s) reviewed', 'cbxmcratingreview' ),
				'score'                              => esc_html__( 'Score', 'cbxmcratingreview' ),
				'post'                               => esc_html__( 'Post', 'cbxmcratingreview' ),
				'user'                               => esc_html__( 'User', 'cbxmcratingreview' ),
				'reviews_submitted'                  => esc_html__( 'review(s) submitted', 'cbxmcratingreview' ),
				'latest_reviews'                     => esc_html__( 'Latest Reviews', 'cbxmcratingreview' ),
				'review_submission_overview'         => esc_html__( 'Review Submission Overview', 'cbxmcratingreview' ),
				'quick_overview_of_monthly'          => esc_html__( 'Quick overview of monthly review submissions.', 'cbxmcratingreview' ),
				'monthly_review_submission_overview' => esc_html__( 'Monthly Review Submission Overview', 'cbxmcratingreview' ),
				'most_recent_reviews'                => esc_html__( 'Most recent reviews', 'cbxmcratingreview' ),
				'edit'                               => esc_html__( 'Edit', 'cbxmcratingreview' ),
				'no_reviews_found'                   => esc_html__( 'No reviews found', 'cbxmcratingreview' ),
				'no_feedbacks_found'                 => esc_html__( 'No feedbacks found', 'cbxmcratingreview' ),
				'no_comments_found'                  => esc_html__( 'No comments found', 'cbxmcratingreview' ),

			],
			'dashboard_data'  => self::getAdminDashboardData(),
			'review_statuses' => CBXMCRatingReviewHelper::ReviewStatusOptions(),
		];

		$js_translations = array_merge_recursive( $common_js_translations, $tools_js_translations );

		return apply_filters( 'cbxmcratingreview_dashboard_js_translation', $js_translations );
	}//end function cbxmcratingreview_dashboard_js_translation

	/**
	 * get job dashboard data
	 *
	 */
	public static function getAdminDashboardData() {
		try {
			$data = [
				'form_count'    => CBXMCRatingReviewHelper::getRatingForms_Count(),
				'review_count'  => CBXMCRatingReviewHelper::totalReviewsCount(),
				'post_reviewed' => CBXMCRatingReviewHelper::getTotalReviewedPostCount()
			];

			return $data;
		} catch ( Exception ) {
			return [];
		}
	}//end method getAdminDashboardData

	/**
	 * All migration files(may include file names from other addon or 3rd party addons))
	 *
	 * @return mixed
	 */
	public static function migration_files() {
		$migration_files = MigrationManage::migration_files();//migrations from core files

		return apply_filters( 'cbxmcratingreview_migration_files', $migration_files );
	}//end method migration_files

	/**
	 * Migration files left
	 *
	 * @return mixed
	 */
	public static function migration_files_left() {
		$migration_files_left = MigrationManage::migration_files_left();

		return apply_filters( 'cbxmcratingreview_migration_files_left', $migration_files_left );
	}//end method migration_files_left

	/**
	 * dashboard Reviews array
	 *
	 * @return mixed
	 */
	public static function dashboardReviews() {
		$logs = RatingReviewLog::query()->with( 'user', 'post' )->orderBy( 'id', 'DESC' )->take( 10 );

		return $logs->get()->toArray();
	}//end method dashboardReviews
}//end class CBXMCRatingReviewAdminHelper