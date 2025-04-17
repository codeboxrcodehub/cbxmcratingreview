<?php

namespace CBX\MCRatingReview\Helpers;

use CBX\MCRatingReview\CBXMCRatingReviewSettings;
use CBX\MCRatingReview\CBXMCRatingReviewPublic;
use CBX\MCRatingReview\Models\RatingReviewForm;
use CBX\MCRatingReview\Models\RatingReviewLogAvg;
use CBX\MCRatingReview\Models\RatingReviewLog;
use CBX\MCRatingReview\MigrationManage;
use Exception;
use Illuminate\Database\QueryException;

use Illuminate\Database\Capsule\Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Helper class
 *
 * Class CBXMCRatingReviewHelper
 */
class CBXMCRatingReviewHelper {

	/**
	 * Load ORM
	 *
	 * @since  1.0.0
	 */
	public static function load_orm() {

		/**
		 * Init DB in ORM
		 */
		global $wpdb;

		$capsule = new Manager();

		$connection_params = [
			'driver'   => 'mysql',
			'host'     => DB_HOST,
			'database' => DB_NAME,
			'username' => DB_USER,
			'password' => DB_PASSWORD,
			'prefix'   => $wpdb->prefix,
		];

		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( DB_CHARSET != '' ) {
				$connection_params['charset'] = DB_CHARSET;
			}

			if ( DB_COLLATE != '' ) {
				$connection_params['collation'] = DB_COLLATE;
			}
		}

		$capsule->addConnection( apply_filters( 'cbxmcratingreview_database_connection_params', $connection_params ) );

		$capsule->setAsGlobal();
		$capsule->bootEloquent();
	} //end method load_orm

	/**
	 * Php and js based redirect method based on situation
	 *
	 * @param $url
	 */
	public static function redirect( $url ) {
		if ( headers_sent() ) {
			$string = '<script type="text/javascript">';
			$string .= 'window.location = "' . $url . '"';
			$string .= '</script>';

			echo esc_html( $string );
		} else {
			wp_safe_redirect( $url );
		}
		exit;
	}//end method redirect

	/**
	 * acceptable image ext
	 * @return array
	 */
	public static function imageExtArr() {
		return [ 'jpg', 'jpeg', 'gif', 'png' ];
	}//end method imageExtArr

	/**
	 * Returns filtered array of post types in plain list
	 *
	 * @param array $filter
	 *
	 * @return array
	 */
	public static function post_types_filtered( $filter = [] ) {
		$post_types          = CBXMCRatingReviewHelper::post_types( true );
		$post_types_filtered = [];
		if ( is_array( $filter ) && sizeof( $filter ) > 0 ) {
			foreach ( $post_types as $key => $value ) {
				if ( in_array( $key, $filter ) ) {
					$post_types_filtered[ $key ] = $value;
				}
			}
		}//if filter has item

		return $post_types_filtered;
	}//end method post_types

/**
	 * Returns post types as array
	 *
	 * @return array
	 */
	public static function post_types( $plain = true ) {
		$post_type_args = [
			'builtin' => [
				'options' => [
					'public'   => true,
					'_builtin' => true,
					'show_ui'  => true,
				],
				'label'   => esc_html__( 'Built in post types', 'cbxmcratingreview' ),
			]

		];

		$post_type_args = apply_filters( 'cbxmcratingreview_post_types_args', $post_type_args );

		$output   = 'objects'; // names or objects, note names is the default
		$operator = 'and';     // 'and' or 'or'

		$postTypes = [];

		if ( $plain ) {
			foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
				$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

				if ( ! empty( $types ) ) {
					foreach ( $types as $type ) {
						//$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
						$postTypes[ $type->name ] = $type->labels->name;
					}
				}
			}
		} else {
			foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
				$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

				if ( ! empty( $types ) ) {
					foreach ( $types as $type ) {
						//$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
						$postTypes[ esc_attr( $postArgTypeArr['label'] ) ][ $type->name ] = $type->labels->name;
					}
				}
			}
		}


		return apply_filters( 'cbxmcratingreview_post_types', $postTypes, $plain );
	}//end method post_types_filtered

	/**
	 * Time to human readable time
	 *
	 * @param        $ts
	 * @param string $fallback_format
	 *
	 * @return false|string
	 */
	public static function time2str( $ts, $fallback_format = 'M j, Y H:i' ) {
		if ( ! ctype_digit( $ts ) ) {
			$ts = strtotime( $ts );
		}
		$diff = time() - $ts;
		if ( $diff == 0 ) {
			return esc_html__( 'now', 'cbxmcratingreview' );
		} elseif ( $diff > 0 ) {
			$day_diff = floor( $diff / 86400 );
			if ( $day_diff == 0 ) {
				if ( $diff < 60 ) {
					return esc_html__( 'just now', 'cbxmcratingreview' );
				}
				if ( $diff < 120 ) {
					return esc_html__( '1 minute ago', 'cbxmcratingreview' );
				}
				if ( $diff < 3600 ) {
					/* translators: %s: How many minutes  */
					return sprintf( esc_html__( '%s minutes ago', 'cbxmcratingreview' ), floor( $diff / 60 ) );
				}
				if ( $diff < 7200 ) {
					return esc_html__( '1 hour ago', 'cbxmcratingreview' );
				}
				if ( $diff < 86400 ) {
					return floor( $diff / 3600 ) . ' hours ago';
				}
			}
			if ( $day_diff == 1 ) {
				return esc_html__( 'Yesterday', 'cbxmcratingreview' );
			}
			if ( $day_diff < 7 ) {
				/* translators: %s: How many days  */
				return sprintf( esc_html__( '%s days ago', 'cbxmcratingreview' ), $day_diff );
			}
			if ( $day_diff < 31 ) {
				/* translators: %s: How many weeks  */
				return sprintf( esc_html__( '%s weeks ago', 'cbxmcratingreview' ), ceil( $day_diff / 7 ) );
			}
			if ( $day_diff < 60 ) {
				return esc_html__( 'last month', 'cbxmcratingreview' );
			}

			return gmdate( $fallback_format, $ts );
		} else {
			$diff     = abs( $diff );
			$day_diff = floor( $diff / 86400 );
			if ( $day_diff == 0 ) {
				if ( $diff < 120 ) {
					return esc_html__( 'in a minute', 'cbxmcratingreview' );
				}
				if ( $diff < 3600 ) {
					/* translators: %s: How many minutes  */
					return sprintf( esc_html__( 'in %s minutes', 'cbxmcratingreview' ), floor( $diff / 60 ) );
				}
				if ( $diff < 7200 ) {
					return esc_html__( 'in an hour', 'cbxmcratingreview' );
				}
				if ( $diff < 86400 ) {
					/* translators: %s: How many hours  */
					return sprintf( esc_html__( 'in %s hours', 'cbxmcratingreview' ), floor( $diff / 3600 ) );
				}
			}
			if ( $day_diff == 1 ) {
				return esc_html__( 'Tomorrow', 'cbxmcratingreview' );
			}
			if ( $day_diff < 4 ) {
				return gmdate( 'l', $ts );
			}
			if ( $day_diff < 7 + ( 7 - gmdate( 'w' ) ) ) {
				return esc_html__( 'next week', 'cbxmcratingreview' );
			}
			if ( ceil( $day_diff / 7 ) < 4 ) {
				/* translators: %s: How many weeks  */
				return sprintf( esc_html__( 'in %s weeks', 'cbxmcratingreview' ), ceil( $day_diff / 7 ) );
			}
			if ( gmdate( 'n', $ts ) == gmdate( 'n' ) + 1 ) {
				return esc_html__( 'next month', 'cbxmcratingreview' );
			}

			return gmdate( $fallback_format, $ts );
		}
	}//end method user_roles

	/**
	 * Add all common js and css needed for review and rating
	 */
	public static function AddJsCss() {
		$plugin_public = new CBXMCRatingReviewPublic();
		$plugin_public->enqueue_common_js_css_rating();
	}//end method time2str

	/**
	 * Add all js and css needed for review submit form
	 */
	public static function AddRatingFormJsCss() {
		$plugin_public = new CBXMCRatingReviewPublic();
		$plugin_public->enqueue_ratingform_js_css_rating();
	}//end method AddJsCss

	/**
	 * Add all js and css needed for review edit form
	 */
	public static function AddRatingEditFormJsCss() {
		$plugin_public = new CBXMCRatingReviewPublic();
		$plugin_public->enqueue_ratingeditform_js_css_rating();
	}//end method AddRatingFormJsCss

	/**
	 * Returns rating hints keys
	 *
	 * @return array
	 */
	public static function ratingHints() {
		$rating_hints = [
			esc_html__( 'Bad', 'cbxmcratingreview' ),
			esc_html__( 'Poor', 'cbxmcratingreview' ),
			esc_html__( 'Regular', 'cbxmcratingreview' ),
			esc_html__( 'Good', 'cbxmcratingreview' ),
			esc_html__( 'Gorgeous', 'cbxmcratingreview' ),
		];

		return apply_filters( 'cbxmcratingreview_rating_hints', $rating_hints );
	}//end method AddRatingEditFormJsCss

	/**
	 * Default star titles
	 *
	 * @return array
	 */
	public static function star_default_titles() {
		return apply_filters( 'cbxmcratingreview_default_star_titles', [
			esc_html__( 'Worst', 'cbxmcratingreview' ),
			esc_html__( 'Bad', 'cbxmcratingreview' ),
			esc_html__( 'Not Bad', 'cbxmcratingreview' ),
			esc_html__( 'Good', 'cbxmcratingreview' ),
			esc_html__( 'Best', 'cbxmcratingreview' )
		] );
	}//end method ratingHints

	/**
	 * Rating hints colors
	 *
	 * @return array
	 */
	public static function ratingHintsColors() {
		$rating_hints_colors = [ '#57bb8a', '#9ace6a', '#ffcf02', '#ff9f02', '#ff6f31' ];

		return apply_filters( 'cbxmcratingreview_rating_hints_colors', $rating_hints_colors );
	}//end method star_default_titles

	/**
	 * all posible status for a review
	 * @return array
	 */
	public static function FormStatusOptions() {
		$exprev_status_arr = [
			'0' => esc_html__( 'Disabled', 'cbxmcratingreview' ),
			'1' => esc_html__( 'Enabled', 'cbxmcratingreview' ),
			//'2' => esc_html__( 'Unpublished', 'cbxmcratingreview' ),
			//'3' => esc_html__( 'Spam', 'cbxmcratingreview' ),
		];

		return apply_filters( 'cbxmcratingreview_review_form_status_options', $exprev_status_arr );
	}//end method ratingHintsColors

/**
	 * all posible status for a review
	 * @return array
	 */
	public static function ReviewPositiveScores() {
		$exprev_status_arr = [
			'1' => esc_html__( '1 or above', 'cbxmcratingreview' ),
			'2' => esc_html__( '2 or above', 'cbxmcratingreview' ),
			'3' => esc_html__( '3 or above', 'cbxmcratingreview' ),
			'4' => esc_html__( '4 or above', 'cbxmcratingreview' ),
			'5' => esc_html__( '5', 'cbxmcratingreview' ),
		];

		return apply_filters( 'cbxmcratingreview_review_review_status_options', $exprev_status_arr );
	}//end method ReviewStatusOptions

	/**
	 * Return all meta keys created by this plugin
	 */
	public static function getMetaKeys() {
		$meta_keys = [];

		$meta_keys['_cbxmcratingreview_avg']   = esc_html__( 'Post rating avg', 'cbxmcratingreview' );                 //todo: for per form these meta keys may change
		$meta_keys['_cbxmcratingreview_total'] = esc_html__( 'Post total Rating/reviews count', 'cbxmcratingreview' ); //todo: for per form these meta keys may change

		return apply_filters( 'cbxmcratingreview_meta_keys', $meta_keys );
	}//end method ReviewStatusOptions

	/**
	 * Get all  core tables list(key and db table name)
	 */
	public static function getAllDBTablesList() {
		global $wpdb;

		//tables
		$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';
		$table_rating_log  = $wpdb->prefix . 'cbxmcratingreview_log';
		$table_rating_avg  = $wpdb->prefix . 'cbxmcratingreview_log_avg';


		$table_names = [];

		$table_names['form'] = $table_rating_form;
		$table_names['log']  = $table_rating_log;
		$table_names['avg']  = $table_rating_avg;

		return apply_filters( 'cbxmcratingreview_table_list', $table_names );
	}//end method ReviewPositiveScores

/**
	 * Get all core table keys (key and names)
	 *
	 * @return mixed|void
	 */
	public static function getAllDBTablesKeyList() {
		$table_key_names         = [];
		$table_key_names['form'] = esc_html__( 'Rating Form Table', 'cbxmcratingreview' );
		$table_key_names['log']  = esc_html__( 'Review Log Table', 'cbxmcratingreview' );
		$table_key_names['avg']  = esc_html__( 'Review Avg Table', 'cbxmcratingreview' );


		return apply_filters( 'cbxmcratingreview_table_key_names', $table_key_names );
	}//end method getMetaKeys

	/**
	 * List all global option name with prefix cbxpoll_
	 */
	public static function getAllOptionNames() {
		global $wpdb;

		$prefix = 'cbxmcratingreview_';
		$wild   = '%';
		$like   = $wpdb->esc_like( $prefix ) . $wild;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$option_names = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE %s", $like ), ARRAY_A );

		return apply_filters( 'cbxmcratingreview_option_names', $option_names );
	}//end function getAllDBTablesList

	/**
	 * Render single review by review id
	 *
	 * @param int $review_id
	 *
	 * @return string
	 */
	public static function singleReviewRender( $review_id = 0 ) {
		$single_review_html = '';

		if ( is_numeric( $review_id ) ) {
			$post_review = self::singleReview( intval( $review_id ) );
		} else {
			$post_review = $review_id;
		}

		if ( ! is_null( $post_review ) ) {

			$single_review_html = cbxmcratingreview_get_template_html( 'rating-review-reviews-list-item.php', [
					'post_review' => $post_review,
				]
			);
		}

		return $single_review_html;
	}//end method getAllDBTablesKeyList

	/**
	 * Get Single review by review id
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	public static function singleReview( $review_id = 0 ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
		$table_users      = $wpdb->prefix . 'users';

		$review_id = intval( $review_id );

		$single_review = null;
		if ( $review_id > 0 ) {
			$join = $where_sql = $sql_select = '';
			$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$where_sql = $wpdb->prepare( "log.id = %d", $review_id );

			$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared , WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
			$single_review = $wpdb->get_row( "$sql_select $join WHERE $where_sql ", 'ARRAY_A' );
			if ( $single_review !== null ) {
				$single_review['attachment']  = maybe_unserialize( $single_review['attachment'] );
				$single_review['extraparams'] = maybe_unserialize( $single_review['extraparams'] );
			}
		}

		return $single_review;
	}//end method getAllOptionNames

	/**
	 * Render the review filter template
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param int $perpage
	 * @param int $page
	 * @param string $score
	 * @param string $order_by
	 * @param string $order
	 */
	public static function postReviewsFilterRender( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $score = '', $order_by = 'id', $order = 'DESC' ) {

		global $current_user;
		$ok_to_render = false;

		$settings = new CBXMCRatingReviewSettings();


		$post_reviews_filter_html = '';

		$post_id   = intval( $post_id );
		$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = [ 'guest' ];
		} else {
			$userRoles = $current_user->roles;
		}


		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return $post_reviews_filter_html;
			} else {
				$form_id = $default_form;
			}
		}

		$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );

		//check if post type supported
		$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : [];


		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end post type check

		//check if user role supported
		if ( $ok_to_render ) {
			//$user_roles_rate = $settings->get_field( 'user_roles_view', 'cbxmcratingreview_common_config', [] );
			$user_roles_rate = isset( $form['user_roles_view'] ) ? $form['user_roles_view'] : [];

			if ( ! is_array( $user_roles_rate ) ) {
				$user_roles_rate = [];
			}

			$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
			if ( sizeof( $intersectedRoles ) == 0 ) {
				$ok_to_render = false;
			}
		}//end check user role support


		$total_reviews = cbxmcratingreview_totalPostReviewsCount( $form_id, $post_id, 1 );

		if ( $ok_to_render ) {

			$post_reviews_filter_html = cbxmcratingreview_get_template_html( 'rating-review-reviews-list-filter.php', [
					'settings'      => $settings,
					'total_reviews' => $total_reviews,
					'post_id'       => $post_id,
					'form_id'       => $form_id,
					'perpage'       => $perpage,
				]
			);
		}

		return $post_reviews_filter_html;
	}//end method singleReview

	/**
	 * Get Rating form data by id
	 *
	 * @param      $id
	 * @param bool $is_object
	 *
	 * @return array|object|null
	 */
	public static function getRatingForm( $id ) {
		try {

			$form_default = CBXMCRatingReviewHelper::form_default_fields();
			$result       = RatingReviewForm::find( $id );

			if ( is_null( $result ) ) {
				return [];
			}

			$result = $result->toArray();

			$result["custom_criteria"] = maybe_unserialize( $result["custom_criteria"] );
			$result["custom_question"] = maybe_unserialize( $result["custom_question"] );

			$extra_fields = maybe_unserialize( $result['extrafields'] );
			$extra_fields = (array) $extra_fields;

			$result = array_merge( $result, $extra_fields );
			foreach ( $form_default as $key => $field ) {
				if ( $field['type'] == 'select' && isset( $field['multiple'] ) && $field['multiple'] == true ) {
					if ( isset( $result[ $key ] ) ) {
						$result[ $key ] = maybe_unserialize( $result[ $key ] ); // warning for new field
					} else {
						$result[ $key ] = $field['default']; // warning for new field
					}
				}
			}

			$custom_criterias = $result["custom_criteria"];
			foreach ( $custom_criterias as $criteria_index => $custom_criteria ) {
				$criteria_id     = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $criteria_index );
				$stars           = isset( $custom_criteria['stars'] ) ? $custom_criteria['stars'] : [];
				$stars_formatted = [];
				if ( is_array( $stars ) && sizeof( $stars ) > 0 ) {
					foreach ( $stars as $star_index => $star ) {

						/* translators: %d: Star Count  */
						$title = isset( $star['title'] ) ? esc_attr( $star['title'] ) : sprintf( esc_html__( 'Star %d', 'cbxmcratingreview' ), ( $star_index + 1 ) );

						$stars_formatted[ $star_index ] = $title;
					}
				}

				$stars_length = sizeof( $stars_formatted );

				$stars_summary           = [];
				$stars_summary['length'] = intval( $stars_length );
				$stars_summary['stars']  = $stars_formatted;

				$custom_criterias[ $criteria_index ]['stars_formatted'] = $stars_summary;

			}

			$result["custom_criteria"] = $custom_criterias;

			return apply_filters( 'cbxmcratingreview_get_ratingForm', $result, $id );

		} catch ( QueryException $e ) {
			// Check if the error is due to a missing table
			if ( str_contains( $e->getMessage(), 'Base table or view not found' ) ) {
				//error_log( esc_html__( 'Form table does not exist. Please check the database structure.', 'cbxmcratingreview' ) );

				return [];
			}
		} catch ( Exception $e ) {
			return [];
		}

	}//end method singleReviewRender

/**
	 * Rating form default fields
	 *
	 * @return array
	 */
	public static function form_default_fields() {
		$form_default = [
			'id'   => [
				'type'    => 'hidden',
				'default' => 0,

			],
			'name' => [
				'label'       => esc_html__( 'Form Title', 'cbxmcratingreview' ),
				'desc'        => esc_html__( 'Write rating form name', 'cbxmcratingreview' ),
				'type'        => 'text',
				'default'     => esc_html__( 'Example Rating Form', 'cbxmcratingreview' ),
				'placeholder' => esc_html__( 'Rating Form Name', 'cbxmcratingreview' ),
				'required'    => true,
				'min'         => 5,
				'max'         => 500,
				'errormsg'    => esc_html__( 'Form title missing or empty, maximum length 500, minimum length 5', 'cbxmcratingreview' ),
				'com_field'   => true
			],

			'status' => [
				'label'    => esc_html__( 'Form Status', 'cbxmcratingreview' ),
				'desc'     => esc_html__( 'Enable disable the form', 'cbxmcratingreview' ),
				'type'     => 'radio',
				'default'  => 1,
				'required' => false,
				'options'  => [
					'1' => esc_html__( 'Enabled', 'cbxmcratingreview' ),
					'0' => esc_html__( 'Disabled', 'cbxmcratingreview' )
				]

			] // create the form but will be active or inactive
		];

		$default_extra_fields = CBXMCRatingReviewHelper::form_default_extra_fields();

		return array_merge( $form_default, $default_extra_fields );
	}//end method postReviews

	/**
	 * Core extra fields
	 *
	 * @return array|mixed|void
	 */
	public static function form_default_extra_fields() {
		$post_types = CBXMCRatingReviewHelper::post_types( true );

		$user_roles_no_guest   = CBXMCRatingReviewHelper::user_roles( true, false );
		$user_roles_with_guest = CBXMCRatingReviewHelper::user_roles( true, true );

		//9 default extra fields  //note review field is now separated
		$default_extra_fields = [
			'question_last_count' => [
				'type'       => 'hidden',
				'default'    => 1,
				'id'         => 'question_last_count',
				'extrafield' => true
			],
			'criteria_last_count' => [
				'type'       => 'hidden',
				'default'    => 2,
				'id'         => 'criteria_last_count',
				'extrafield' => true
			],

			'post_types' => [
				'label'       => esc_html__( 'Post Type Support', 'cbxmcratingreview' ),
				'desc'        => htmlspecialchars_decode( esc_html__( 'Which post types can have the rating & review features. Please make sure multiple form is not associated with same post type for best performance.', 'cbxmcratingreview' ) ),
				'type'        => 'select',
				'multiple'    => true,
				'default'     => [ 'post' ],
				'placeholder' => esc_html__( 'Choose post type(s)...', 'cbxmcratingreview' ),
				'required'    => true,
				'options'     => $post_types,
				'errormsg'    => esc_html__( 'Post type is missing or at least one post type must be selected', 'cbxmcratingreview' ),
				'extrafield'  => true,
				'com_field'   => true
			],

			'user_roles_rate'         => [
				'label'       => htmlspecialchars_decode( esc_html__( 'Who Can give Rate & Review', 'cbxmcratingreview' ) ),
				'desc'        => esc_html__( 'Which user role will have vote capability', 'cbxmcratingreview' ),
				'type'        => 'select',
				'placeholder' => esc_html__( 'Choose User Group ...', 'cbxmcratingreview' ),
				'multiple'    => true,
				'default'     => [ 'administrator', 'editor', 'author', 'contributor', 'subscriber' ],
				'required'    => true,
				'options'     => $user_roles_no_guest,
				'errormsg'    => esc_html__( 'User role missing or at least one user role must be selected', 'cbxmcratingreview' ),
				'extrafield'  => true,
				'com_field'   => true
			],
			'user_roles_view'         => [
				'label'       => htmlspecialchars_decode( esc_html__( 'Who Can View Rating & Review', 'cbxmcratingreview' ) ),
				'desc'        => esc_html__( 'Which user role will have view capability', 'cbxmcratingreview' ),
				'type'        => 'select',
				'placeholder' => esc_html__( 'Choose User Group ...', 'cbxmcratingreview' ),
				'multiple'    => true,
				'default'     => [ 'administrator', 'editor', 'author', 'contributor', 'subscriber', 'guest' ],
				'required'    => true,
				'options'     => $user_roles_with_guest,
				'errormsg'    => esc_html__( 'User role missing or at least one user role must be selected', 'cbxmcratingreview' ),
				'extrafield'  => true,
				'com_field'   => true
			],
			'enable_auto_integration' => [
				'label'      => esc_html__( 'Enable Auto Integration', 'cbxmcratingreview' ),
				'desc'       => esc_html__( 'Enable/disable auto integration, ie, add average rating before post content in archive, in details article mode add average rating information before content, rating form & review listing after content', 'cbxmcratingreview' ),
				'type'       => 'radio',
				'default'    => 1,
				'options'    => [
					'1' => esc_html__( 'On', 'cbxmcratingreview' ),
					'0' => esc_html__( 'Off', 'cbxmcratingreview' ),
				],
				'extrafield' => true,
			],
			'post_types_auto'         => [
				'label'       => esc_html__( 'Auto Integration for Post Type', 'cbxmcratingreview' ),
				'desc'        => __( 'Enable which post types will have auto integration features. Please note that selected post types should be within the post types selected for <strong>Post Type Support</strong>', 'cbxmcratingreview' ),
				'type'        => 'select',
				'multiple'    => true,
				'default'     => [],
				'placeholder' => esc_html__( 'Choose post type(s)...', 'cbxmcratingreview' ),
				'options'     => [],
				'errormsg'    => esc_html__( 'Post type is missing or at least one post type must be selected', 'cbxmcratingreview' ),
				'extrafield'  => true,
			],
			'show_on_single'          => [
				'label'      => esc_html__( 'Show on Single', 'cbxmcratingreview' ),
				'desc'       => esc_html__( 'Enable disable for single article(post, page or any custom post type), related with auto integration.', 'cbxmcratingreview' ),
				'type'       => 'radio',
				'default'    => 1,
				'required'   => false,
				'options'    => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' )
				],
				'extrafield' => true,
				'com_field'  => true
			],
			'show_on_home'            => [
				'label'      => esc_html__( 'Show on Home/Frontpage', 'cbxmcratingreview' ),
				'desc'       => esc_html__( 'Enable disable for home/frontpage, related with auto integration.', 'cbxmcratingreview' ),
				'type'       => 'radio',
				'default'    => 1,
				'required'   => false,
				'options'    => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' )
				],
				'extrafield' => true,
				'com_field'  => true
			], //show on home or frontpage
			'show_on_arcv'            => [
				'label'      => esc_html__( 'Show on Archives', 'cbxmcratingreview' ),
				'desc'       => esc_html__( 'Enable disable for archive pages, related with auto integration.', 'cbxmcratingreview' ),
				'type'       => 'radio',
				'default'    => 1,
				'required'   => false,
				'options'    => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' )
				],
				'extrafield' => true,
				'com_field'  => true
			], //show on any kind of archive
			'enable_question'         => [
				'label'      => esc_html__( 'Enable Question', 'cbxmcratingreview' ),
				'desc'       => esc_html__( 'Enable Question with Rating', 'cbxmcratingreview' ),
				'type'       => 'radio',
				'default'    => 1,
				'required'   => true,
				'options'    => [
					'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
					'0' => esc_html__( 'No', 'cbxmcratingreview' )
				],
				'errormsg'   => esc_html__( 'Enable question field is missing or value must be 0 or 1', 'cbxmcratingreview' ),
				'extrafield' => true
			], // Enable Questions
		];


		return apply_filters( 'cbxmcratingreview_default_extra_fields', $default_extra_fields );
	}//end method postReviewsFilterRender

	/**
	 * Get the user roles
	 *
	 * @param string $useCase
	 *
	 * @return array
	 */
	public static function user_roles( $plain = true, $include_guest = false ) {
		global $wp_roles;

		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );

		}

		$userRoles = [];
		if ( $plain ) {
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				$userRoles[ $role ] = $roleInfo['name'];
			}
			if ( $include_guest ) {
				$userRoles['guest'] = esc_html__( "Guest", 'cbxmcratingreview' );
			}
		} else {
			//optgroup
			$userRoles_r = [];
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				$userRoles_r[ $role ] = $roleInfo['name'];
			}

			$userRoles = [
				'Registered' => $userRoles_r,
			];

			if ( $include_guest ) {
				$userRoles['Anonymous'] = [
					'guest' => esc_html__( "Guest", 'cbxmcratingreview' )
				];
			}
		}

		return apply_filters( 'cbxmcratingreview_userroles', $userRoles, $plain, $include_guest );
	}//end method postReviewsRender

/**
	 * render Review lists data of a Post
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param int $perpage
	 * @param int $page
	 * @param string $status
	 * @param string $score
	 * @param string $order_by
	 * @param string $order
	 * @param bool $load_more
	 * @param bool $show_filter
	 *
	 * @return string
	 */
	public static function postReviewsRender( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $order_by = 'id', $order = 'DESC', $load_more = false ) {
		global $current_user;
		$ok_to_render = false;

		$settings = new CBXMCRatingReviewSettings();

		$post_reviews_html = '';

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return $post_reviews_html;
			} else {
				$form_id = $default_form;
			}
		}

		$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );

		$post_id   = intval( $post_id );
		$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = [ 'guest' ];
		} else {
			$userRoles = $current_user->roles;
		}


		//check if post type supported
		//$post_types_supported = $settings->get_field( 'post_types', 'cbxmcratingreview_common_config', [] );
		$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : [];


		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end post type check


		//check if user role supported
		if ( $ok_to_render ) {
			//$user_roles_rate = $settings->get_field( 'user_roles_view', 'cbxmcratingreview_common_config', [] );
			$user_roles_rate = isset( $form['user_roles_view'] ) ? $form['user_roles_view'] : [];

			if ( ! is_array( $user_roles_rate ) ) {
				$user_roles_rate = [];
			}

			$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
			if ( sizeof( $intersectedRoles ) == 0 ) {
				$ok_to_render = false;
			}
		}//end check user role support

		if ( $ok_to_render ) {

			$post_reviews = self::postReviews( $form_id, $post_id, $perpage, $page, $status, $score, $order_by, $order );


			if ( $load_more ) {
				$post_reviews_html .= cbxmcratingreview_get_template_html( 'rating-review-reviews-list.php', [
						'settings'     => $settings,
						'post_reviews' => $post_reviews,
						'post_id'      => $post_id,
					]
				);
			} else {

				if ( sizeof( $post_reviews ) > 0 ) {
					foreach ( $post_reviews as $index => $post_review ) {
						$review_list_item_class = apply_filters( 'cbxmcratingreview_review_list_item_class', 'cbxmcratingreview_review_list_item' );

						$post_reviews_html .= '<li id="cbxmcratingreview_review_list_item_' . intval( $post_review['id'] ) . '"  class="' . $review_list_item_class . '">';
						$post_reviews_html .= cbxmcratingreview_get_template_html( 'rating-review-reviews-list-item.php', [
								'settings'    => $settings,
								'post_review' => $post_review,
								'post_id'     => $post_id,
							]
						);

						$post_reviews_html .= '</li>';
					}
				} else {
					$review_list_item_class_notfound_class = apply_filters( 'cbxmcratingreview_review_list_item_class_notfound_class', 'cbxmcratingreview_review_list_item cbxmcratingreview_review_list_item_notfound' );
					$post_reviews_html                     .= '<li class="' . esc_attr( $review_list_item_class_notfound_class ) . '"> ';
					$post_reviews_html                     .= '<p class="no_reviews_found">' . esc_html__( 'No reviews yet!', 'cbxmcratingreview' ) . '</p>';
					$post_reviews_html                     .= '</li>';

				}
			}

			if ( $load_more ) {
				$total_count   = cbxmcratingreview_totalPostReviewsCount( $form_id, $post_id, $status, $score );
				$maximum_pages = ceil( $total_count / $perpage );

				$post_reviews_html .= cbxmcratingreview_get_template_html( 'rating-review-reviews-list-more.php', [
						'settings'      => $settings,
						'maximum_pages' => $maximum_pages,
						'post_id'       => $post_id,
						'form_id'       => $form_id,
						'perpage'       => $perpage,
						'page'          => $page,
						'order_by'      => $order_by,
						'order'         => $order,
						'score'         => $score
					]
				);
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				//echo $post_reviews_html;

			}
		}

		return $post_reviews_html;
	}//end method Reviews

/**
	 * Review lists data of a Post
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param int $perpage
	 * @param int $page
	 * @param string $status
	 * @param string $score
	 * @param string $order_by
	 * @param string $order
	 *
	 * @return array|null|object
	 */
	public static function postReviews( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $order_by = 'id', $order = 'DESC' ) {

		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
		$table_users      = $wpdb->prefix . 'users';

		$settings = new CBXMCRatingReviewSettings();

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return null;
			} else {
				$form_id = $default_form;
			}
		}

		$order_by = ( $order_by == '' ) ? 'id' : $order_by;
		$order    = ( $order == '' ) ? 'DESC' : $order;

		$post_reviews = null;

		if ( $post_id > 0 ) {
			$join = $where_sql = $sql_select = '';
			$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$where_sql = $wpdb->prepare( "log.post_id=%d", $post_id );
			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}


			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$where_sql .= $wpdb->prepare( ' log.form_id = %s', $form_id );


			if ( $score != '' ) {

				if ( $score == - 1 || $score == - 2 ) {

					$positive_score = intval( $settings->get_field( 'positive_score', 'cbxmcratingreview_common_config', 4 ) );

					//positive or critial score
					if ( $score == - 1 ) {
						//all positives
						if ( $where_sql != '' ) {
							$where_sql .= ' AND ';
						}
						// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$where_sql .= $wpdb->prepare( ' CEIL(log.score) >= %d', $positive_score );
					} elseif ( $score == - 2 ) {
						//all criticals
						if ( $where_sql != '' ) {
							$where_sql .= ' AND ';
						}
						// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$where_sql .= $wpdb->prepare( ' CEIL(log.score) < %d', $positive_score );
					}
				} else {
					//regular score
					$score = ceil( $score );
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %f', $score );
				}
			}


			$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";


			$sorting_order = " ORDER BY $order_by $order ";

			$limit_sql = '';
			if ( $perpage != '-1' ) {
				$perpage     = intval( $perpage );
				$start_point = ( $page * $perpage ) - $perpage;
				$limit_sql   = "LIMIT";
				$limit_sql   .= ' ' . $start_point . ',';
				$limit_sql   .= ' ' . $perpage;
			}

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared , WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
			$post_reviews = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );


			if ( $post_reviews !== null ) {
				foreach ( $post_reviews as &$post_review ) {
					$post_review['attachment']  = maybe_unserialize( $post_review['attachment'] );
					$post_review['extraparams'] = maybe_unserialize( $post_review['extraparams'] );
				}
			}
		}

		return $post_reviews;
	}//end method ReviewsByUser

	/**
	 * All Review lists data
	 *
	 * @param int/string    $form_id
	 * @param int $perpage
	 * @param int $page
	 * @param string $status
	 * @param string $order_by
	 * @param string $order
	 * @param string $score
	 *
	 * @return array|null|object
	 */
	public static function Reviews( $form_id = '', $perpage = 10, $page = 1, $status = '', $order_by = 'id', $order = 'DESC', $score = '' ) {

		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
		$table_users      = $wpdb->prefix . 'users';


		$post_reviews = null;

		$join = $where_sql = $sql_select = '';

		if ( $form_id != '' ) {
			$form_id = intval( $form_id );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$where_sql .= $wpdb->prepare( ' log.form_id = %d ', $form_id );
		}

		if ( $status != '' ) {
			$status = intval( $status );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$where_sql .= $wpdb->prepare( ' log.status = %d', $status );
		}

		if ( $score != '' ) {
			$score = ceil( $score );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %d', $score );
		}

		//$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";
		$sql_select = "SELECT log.* FROM $table_rating_log AS log";

		$sorting_order = " ORDER BY $order_by $order ";

		$limit_sql = '';
		if ( $perpage != '-1' ) {
			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;
		}


		if ( $where_sql == '' ) {
			$where_sql = ' 1 ';
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared , WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
		$post_reviews = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );


		return $post_reviews;
	}//end method totalReviewsCount

	/**
	 * All Review lists data by a User
	 *
	 * @param int/string    $form_id
	 * @param int $user_id
	 * @param int $page
	 * @param int $perpage
	 * @param string $status
	 *
	 * @return array|null|object
	 */
	public static function ReviewsByUser( $form_id = '', $user_id = 0, $perpage = 10, $page = 1, $status = '', $order_by = 'id', $order = 'DESC', $filter_score = '' ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
		//$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';
		$table_users = $wpdb->prefix . 'users';

		$user_id = intval( $user_id );
		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;

		$post_reviews_by_user = null;
		if ( $user_id > 0 ) {
			$join = $where_sql = $sql_select = '';

			$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";
			$join = apply_filters( 'cbxmcratingreview_ReviewsByUser_join', $join, $user_id, $perpage, $page, $status, $order_by, $order );

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$where_sql = $wpdb->prepare( "log.user_id=%d", $user_id );

			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}

			if ( is_numeric( $form_id ) && intval( $form_id ) > 0 ) {
				$form_id = intval( $form_id );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$where_sql .= $wpdb->prepare( ' log.form_id = %d ', $form_id );
			}

			if ( $filter_score != '' ) {
				$filter_score = ceil( $filter_score );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
			}

			$sql_select = "log.*, users.user_email, users.display_name";
			$sql_select = apply_filters( 'cbxmcratingreview_ReviewsByUser_select', $sql_select, $user_id, $perpage, $page, $status, $order_by, $order );

			$sorting_order = " ORDER BY $order_by $order ";

			$limit_sql = '';
			if ( $perpage != '-1' ) {
				$start_point = ( $page * $perpage ) - $perpage;
				$limit_sql   = "LIMIT";
				$limit_sql   .= ' ' . $start_point . ',';
				$limit_sql   .= ' ' . $perpage;
			}

			$where_sql = apply_filters( 'cbxmcratingreview_ReviewsByUser_where', $where_sql, $user_id, $perpage, $page, $status, $order_by, $order );

			if ( $where_sql == '' ) {
				$where_sql = ' 1 ';
			}

			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared , WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
			$post_reviews_by_user = $wpdb->get_results( "SELECT $sql_select FROM $table_rating_log AS log $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );
		}

		return $post_reviews_by_user;
	}//end method totalReviewsCountPostType

	/**
	 * Total reviews count
	 *
	 * @param int/string $form_id
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return int
	 */
	public static function totalReviewsCount( $form_id = 0, $status = null, $filter_score = '' ) {

		$reviews = RatingReviewLog::query();

		if ( $form_id != 0 ) {
			$form_id = intval( $form_id );
			$reviews = $reviews->where( 'form_id', $form_id );
		}

		if ( $status != null ) {
			$status  = intval( $status );
			$reviews = $reviews->where( 'status', $status );
		}

		if ( $filter_score != '' ) {
			$filter_score = ceil( $filter_score );
			$reviews      = $reviews->whereRaw( 'CEIL(score) = ?', [ $filter_score ] );
		}

		return $reviews->count();
	}//end method totalReviewsCountByUser

	/**
	 * Total reviews count
	 *
	 * @param int/string $form_id
	 * @param string $post_type
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return int
	 */
	public static function totalReviewsCountPostType( $form_id = '', $post_type = 'post', $status = null, $filter_score = '' ) {

		$reviews = RatingReviewLog::query();

		if ( $form_id != 0 ) {
			$form_id = intval( $form_id );
			$reviews = $reviews->where( 'form_id', $form_id );
		}

		if ( $status != null ) {
			$status  = intval( $status );
			$reviews = $reviews->where( 'status', $status );
		}

		if ( $filter_score != '' ) {
			$filter_score = ceil( $filter_score );
			$reviews      = $reviews->whereRaw( 'CEIL(score) = ?', [ $filter_score ] );
		}

		if ( $post_type != '' ) {
			$post_type = sanitize_text_field( $post_type );
			$reviews   = $reviews->where( 'post_type', $post_type );
		}

		return $reviews->count();
	}//end method totalPostReviewsCount

	/**
	 * Total reviews count by User
	 *
	 * @param int/string $form_id
	 * @param int $user_id
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return int
	 */
	public static function totalReviewsCountByUser( $form_id = 0, $user_id = 0, $status = null, $filter_score = '' ) {
		$user_id = intval( $user_id );

		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
		if ( $user_id == 0 ) {
			return 0;
		}

		$reviews = RatingReviewLog::query();

		if ( $form_id != 0 ) {
			$form_id = intval( $form_id );
			$reviews = $reviews->where( 'form_id', $form_id );
		}

		if ( $status != null ) {
			$status  = intval( $status );
			$reviews = $reviews->where( 'status', $status );
		}

		if ( $filter_score != '' ) {
			$filter_score = ceil( $filter_score );
			$reviews      = $reviews->whereRaw( 'CEIL(score) = ?', [ $filter_score ] );
		}

		if ( $user_id != 0 ) {
			$reviews = $reviews->where( 'user_id', $user_id );
		}

		return $reviews->count();
	}//end method totalPostReviewsCountByUser

	/**
	 * is this post rated previously
	 *
	 * @param int $form_id
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function isPostRated( $form_id = 0, $post_id = 0 ) {

		$settings = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			$form_id      = $default_form;
		}

		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : intval( $post_id );

		$is_post_rated = false;

		if ( $post_id == 0 || $form_id == 0 ) {
			return false;
		}

		if ( $post_id > 0 ) {
			$is_post_rated = ( self::totalPostReviewsCount( $form_id, $post_id, '' ) > 0 ) ? true : false;
		}

		return $is_post_rated;
	}//end method isPostRated

/**
	 * Total reviews count of a Post
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param string $status
	 * @param string $score
	 *
	 * @return int
	 */
	public static function totalPostReviewsCount( $form_id = 0, $post_id = 0, $status = null, $score = '' ) {
		$reviews  = RatingReviewLog::query();
		$settings = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return 0;
			} else {
				$form_id = $default_form;
			}
		}

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		if ( $post_id == 0 ) {
			return 0;
		}

		if ( $status != null ) {
			$status  = intval( $status );
			$reviews = $reviews->where( 'status', $status );
		}

		if ( $score != '' ) {
			if ( $score == - 1 || $score == - 2 ) {
				$positive_score = intval( $settings->get_field( 'positive_score', 'cbxmcratingreview_common_config', 4 ) );
				//positive or critial score
				if ( $score == - 1 ) {
					$reviews = $reviews->whereRaw( 'CEIL(score) >= ?', [ $positive_score ] );
				} elseif ( $score == - 2 ) {
					$reviews = $reviews->whereRaw( 'CEIL(score) < ?', [ $positive_score ] );
				}
			} else {
				$score   = ceil( $score );
				$reviews = $reviews->whereRaw( 'CEIL(score) = ?', [ $score ] );
			}

		}

		if ( $form_id != 0 ) {
			$form_id = intval( $form_id );
			$reviews = $reviews->where( 'form_id', $form_id );
		}

		$reviews = $reviews->where( 'post_id', $post_id );

		return $reviews->count();
	}//end method isPostRatedByUser

	/**
	 * is this post rated previously by user
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return true/false
	 */
	public static function isPostRatedByUser( $form_id = 0, $post_id = 0, $user_id = 0 ) {

		$settings              = new CBXMCRatingReviewSettings();
		$is_post_rated_by_user = false;

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			$form_id      = $default_form;
		}

		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : intval( $post_id );
		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : intval( $user_id );

		if ( $post_id == 0 || $form_id == 0 || $user_id == 0 ) {
			return $is_post_rated_by_user;
		}

		if ( $post_id > 0 && $user_id > 0 ) {
			$is_post_rated_by_user = ( self::totalPostReviewsCountByUser( $form_id, $post_id, $user_id, '' ) > 0 ) ? true : false;
		}

		return $is_post_rated_by_user;
	}//end method lastPostReviewDateByUser

	/**
	 * Total reviews count of a Post by a User
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param int $user_id
	 * @param string $status
	 *
	 * @return int
	 */
	public static function totalPostReviewsCountByUser( $form_id = 0, $post_id = 0, $user_id = 0, $status = null ) {
		$settings = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return 0;
			} else {
				$form_id = $default_form;
			}
		}

		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : intval( $post_id );
		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : intval( $user_id );

		if ( $post_id == 0 || $user_id == 0 ) {
			return 0;
		}

		$reviews = RatingReviewLog::query();

		if ( $status != null ) {
			$status  = intval( $status );
			$reviews = $reviews->where( 'status', $status );
		}

		$reviews = $reviews->where( 'form_id', $form_id );
		$reviews = $reviews->where( 'post_id', $post_id );
		$reviews = $reviews->where( 'user_id', $user_id );

		return $reviews->count();
	}//end postAvgRatingInfo

	/**
	 * Last Post rate date by a User
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return  datetime
	 */
	public static function lastPostReviewDateByUser( $form_id = 0, $post_id = 0, $user_id = 0 ) {

		$settings = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			$form_id      = $default_form;
		}

		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : intval( $post_id );
		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : intval( $user_id );

		if ( $user_id == 0 || $post_id == 0 || $form_id == 0 ) {
			return null;
		}

		$reviews = RatingReviewLog::query();
		$date    = $reviews->where( [
			'post_id' => $post_id,
			'user_id' => $user_id
		] )->orderBy( 'date_created', 'DESC' )->value( 'date_created' );

		return $date;
	}//end singleAvgRatingInfo

/**
	 * Average rating information of a post
	 *
	 * @param int $form_id
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	public static function postAvgRatingInfo( $form_id = 0, $post_id = 0 ) {
		$post_avg_rating = null;

		$settings = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			$form_id      = $default_form;
		}

		if ( $form_id == 0 ) {
			return $post_avg_rating;
		}

		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : intval( $post_id );


		if ( $post_id > 0 ) {
			$post_avg_rating = RatingReviewLogAvg::where( [ 'post_id' => $post_id, 'form_id' => $form_id ] )->first();
		}


		if ( is_null( $post_avg_rating ) ) {
			$post_avg_rating['id']            = 0;
			$post_avg_rating['form_id']       = $form_id;
			$post_avg_rating['post_id']       = $post_id;
			$post_avg_rating['avg_rating']    = 0;
			$post_avg_rating['total_count']   = 0;
			$post_avg_rating['rating_stat']   = [];
			$post_avg_rating['date_created']  = null;
			$post_avg_rating['date_modified'] = null;

			//extra
			$post_avg_rating['rating_stat_scores'] = [];

		} else {
			$post_avg_rating = $post_avg_rating->toArray();

			$post_avg_rating['rating_stat'] = maybe_unserialize( $post_avg_rating['rating_stat'] );

			if ( isset( $post_avg_rating['rating_stat']['rating_stat_scores'] ) ) {
				$post_avg_rating['rating_stat_scores'] = maybe_unserialize( $post_avg_rating['rating_stat']['rating_stat_scores'] );
			} else {
				$post_avg_rating['rating_stat_scores'] = [];
			}
		}

		return apply_filters( 'cbxmcratingreview_post_avg', $post_avg_rating, $post_id );
	}//end method calculatePostAvg

	/**
	 * Single avg rating info by avg id
	 *
	 * @param int $avg_id
	 *
	 * @return array|null|object|void
	 */
	public static function singleAvgRatingInfo( $avg_id = 0 ) {
		$avg_id = intval( $avg_id );

		$single_avg_rating = null;

		if ( $avg_id > 0 ) {
			$single_avg_rating = RatingReviewLogAvg::find( $avg_id );

			if ( $single_avg_rating != null ) {
				$single_avg_rating = $single_avg_rating->toArray();

				$single_avg_rating['rating_stat'] = maybe_unserialize( $single_avg_rating['rating_stat'] );

				if ( isset( $single_avg_rating['rating_stat']['rating_stat_scores'] ) ) {
					$single_avg_rating['rating_stat_scores'] = maybe_unserialize( $single_avg_rating['rating_stat']['rating_stat_scores'] );
				} else {
					$single_avg_rating['rating_stat_scores'] = [];
				}
			}
		}

		return $single_avg_rating;
	}//end method adjustPostwAvg

	/**
	 * Add or update Avg calculation
	 *
	 * @param $review_info
	 *
	 */
	public static function calculatePostAvg( $review_info ) {

		//we need to calculate avg of avg and same time avg for each single criteria id/criteria

		global $wpdb;
		$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';
		$settings             = new CBXMCRatingReviewSettings();

		$post_id           = intval( $review_info['post_id'] );
		$form_id           = intval( $review_info['form_id'] );
		$score             = $review_info['score'];
		$ceil_rating_score = ceil( $score );
		$ratings           = isset( $review_info['ratings'] ) ? maybe_unserialize( $review_info['ratings'] ) : [];


		$post_avg_rating = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );
		$ratings_stars   = isset( $ratings['ratings_stars'] ) ? maybe_unserialize( $ratings['ratings_stars'] ) : [];

		//if fresh avg calculation
		if ( is_null( $post_avg_rating ) || intval( $post_avg_rating['id'] ) == 0 ) {

			$rating_stat = [];

			//rating score percentage calculation
			$rating_stat_scores                       = [];
			$rating_stat_scores[ $ceil_rating_score ] = [
				'count'   => 1,
				'percent' => 100,
			];
			$rating_stat['rating_stat_scores']        = $rating_stat_scores;

			//calculate criteria based rating stat
			if ( is_array( $ratings_stars ) && sizeof( $ratings_stars ) > 0 ) {
				$criteria_rating_stat_scores = [];
				$criteria_infos              = [];

				foreach ( $ratings_stars as $criteria_id => $ratings_star ) {
					$criteria_score                 = isset( $ratings_star['score_standard'] ) ? $ratings_star['score_standard'] : 0; //score in 5
					$criteria_infos[ $criteria_id ] = [
						'avg_rating'  => $criteria_score,
						'total_count' => 1,
					];

					$criteria_score_ceil                                                 = ceil( $criteria_score );
					$criteria_rating_stat_scores[ $criteria_id ][ $criteria_score_ceil ] = [
						'count'   => 1,
						'percent' => 100,
					];
				}

				$rating_stat['criteria_stat_scores'] = $criteria_rating_stat_scores;
				$rating_stat['criteria_info']        = $criteria_infos;
			}
			//end calculate criteria based rating stat

			// phpcs:ignore  WordPress.DB.DirectDatabaseQuery.DirectQuery
			$avg_insert_status = $wpdb->insert(
				$table_rating_avg_log,
				[
					'post_id'      => $post_id,
					'form_id'      => $form_id,
					'post_type'    => get_post_type( $post_id ),
					'avg_rating'   => $score,
					'total_count'  => 1,
					'date_created' => current_time( 'mysql' ),
					'rating_stat'  => maybe_serialize( $rating_stat ),
				],
				[
					'%d', // post_id,
					'%d', // form_id,
					'%s', // post_type
					'%f', // avg_rating
					'%d', // total_count
					'%s', // date_created
					'%s', // rating_stat
				]
			);

			if ( $avg_insert_status !== false ) {
				//add post avg in post meta key
				update_post_meta( $post_id, '_cbxmcratingreview_avg', $score ); //todo: need ceil ?
				update_post_meta( $post_id, '_cbxmcratingreview_total', 1 );
			}
			//send the currently added review as html
		} else {
			// update avg rating
			$total_score     = ( $post_avg_rating['avg_rating'] * $post_avg_rating['total_count'] ) + $score;
			$total_count_new = intval( $post_avg_rating['total_count'] ) + 1;
			$score_new       = number_format( ( $total_score / $total_count_new ), 2 );


			$rating_stat = maybe_unserialize( $post_avg_rating['rating_stat'] );

			//rating score percentage calculation
			$rating_stat_scores = $rating_stat['rating_stat_scores'];


			if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
				$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) + 1;
				$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
			} else {
				$rating_stat_scores[ $ceil_rating_score ]['count'] = 1;
			}

			//calculate percentage once again
			foreach ( $rating_stat_scores as $score_loop => $count_percent ) {
				$rating_stat_scores[ $score_loop ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score_loop ]['count'] ) / $total_count_new ) * 100, 2 );
			}

			$rating_stat['rating_stat_scores'] = $rating_stat_scores;

			//calculate criteria based rating stat
			$criteria_infos        = isset( $rating_stat['criteria_info'] ) ? $rating_stat['criteria_info'] : [];
			$criteria_infosT       = [];
			$criteria_stat_scores  = isset( $rating_stat['criteria_stat_scores'] ) ? $rating_stat['criteria_stat_scores'] : [];
			$criteria_stat_scoresT = [];

			if ( is_array( $ratings_stars ) && sizeof( $ratings_stars ) > 0 ) {
				foreach ( $ratings_stars as $criteria_id => $ratings_star ) {
					$criteria_info = isset( $criteria_infos[ $criteria_id ] ) ? $criteria_infos[ $criteria_id ] : [];

					$criteria_avg_rating  = isset( $criteria_info['avg_rating'] ) ? $criteria_info['avg_rating'] : 0;
					$criteria_total_count = isset( $criteria_info['total_count'] ) ? $criteria_info['total_count'] : 0;

					$rating_score      = isset( $ratings_star['score_standard'] ) ? $ratings_star['score_standard'] : 0; //score in 5
					$rating_score_ceil = ceil( $rating_score );

					$criteria_total_score     = $criteria_avg_rating * $criteria_total_count;
					$criteria_total_score_new = $criteria_total_score + $rating_score;
					$criteria_total_count_new = intval( $criteria_total_score ) + 1;

					$criteria_avg_rating_new = number_format( ( $criteria_total_score_new / $criteria_total_count_new ), 2 );

					$criteria_infosT[ $criteria_id ] = [
						'avg_rating'  => $criteria_avg_rating_new,
						'total_count' => $criteria_total_count_new,
					];

					$criteria_stat_score = isset( $criteria_stat_scores[ $criteria_id ] ) ? $criteria_stat_scores[ $criteria_id ] : [];


					if ( isset( $criteria_stat_score[ $rating_score_ceil ] ) ) {
						$new_count                                          = intval( $criteria_stat_score[ $rating_score_ceil ]['count'] ) + 1;
						$criteria_stat_score[ $rating_score_ceil ]['count'] = $new_count;
					} else {
						$criteria_stat_score[ $rating_score_ceil ]['count'] = 1;
					}

					//calculate percentage once again
					foreach ( $criteria_stat_score as $score_loop => $count_percent ) {
						$criteria_stat_score[ $score_loop ]['percent'] = number_format( ( intval( $criteria_stat_score[ $score_loop ]['count'] ) / $criteria_total_count_new ) * 100, 2 );
					}

					$criteria_stat_scoresT[ $criteria_id ] = $criteria_stat_score;

				}

				$rating_stat['criteria_info']        = $criteria_infosT;
				$rating_stat['criteria_stat_scores'] = $criteria_stat_scoresT;
			}//end calculate criteria based rating stat

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
			$avg_update_status = $wpdb->update(
				$table_rating_avg_log,
				[
					'avg_rating'    => $score_new,
					'total_count'   => $total_count_new,
					'rating_stat'   => maybe_serialize( $rating_stat ),
					'date_modified' => current_time( 'mysql' ),
				],
				[ 'id' => $post_avg_rating['id'] ],
				[
					'%f', // avg_rating
					'%d', // total_count
					'%s', // rating_stat
					'%s', // date_modifed
				],
				[
					'%d',
				]
			);

			if ( $avg_update_status !== false ) {
				//update post avg in post meta key
				update_post_meta( $post_id, '_cbxmcratingreview_avg', $score_new ); //todo: need ceil ?
				update_post_meta( $post_id, '_cbxmcratingreview_total', $total_count_new );
			}
		}//end avg calculation


	}//end method editPostwAvg

/**
	 * Readjust average after delete of any review or status change to any other state than published(1)
	 *
	 * @param array $review_info
	 *
	 * @return false|int|null
	 */
	public static function adjustPostwAvg( $review_info = [] ) {
		//we need to calculate avg of avg and same time avg for each single criteria id/criteria

		global $wpdb;
		$table_rating_avg = $wpdb->prefix . 'cbxmcratingreview_log_avg';
		$settings         = new CBXMCRatingReviewSettings();


		$post_id = intval( $review_info['post_id'] );
		$form_id = intval( $review_info['form_id'] );

		$post_avg_rating = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );

		$avg_rating        = $post_avg_rating['avg_rating'];
		$total_count       = $post_avg_rating['total_count'];
		$total_count_new   = $total_count - 1;
		$total_score       = $avg_rating * $total_count;
		$score             = $review_info['score'];
		$ceil_rating_score = ceil( $score );

		// avg adjust
		$process_status = null;

		if ( $total_count_new != 0 ) {
			$total_score_new = $total_score - $score;
			$score_new       = number_format( ( $total_score_new / $total_count_new ), 2 );

			$rating_stat = maybe_unserialize( $post_avg_rating['rating_stat'] );

			//rating score percentage calculation
			$rating_stat_scores = isset( $rating_stat['rating_stat_scores'] ) ? $rating_stat['rating_stat_scores'] : [];

			if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
				$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) - 1;
				$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
			}

			//calculate percentage once again
			foreach ( $rating_stat_scores as $score_loop => $count_percent ) {
				$rating_stat_scores[ $score_loop ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score_loop ]['count'] ) / $total_count_new ) * 100, 2 );
			}

			$rating_stat['rating_stat_scores'] = $rating_stat_scores;
			//end rating score percentage calculation

			//calculate criteria based rating stat
			$ratings       = isset( $review_info['ratings'] ) ? maybe_unserialize( $review_info['ratings'] ) : [];
			$ratings_stars = isset( $ratings['ratings_stars'] ) ? maybe_unserialize( $ratings['ratings_stars'] ) : [];

			$criteria_infos       = isset( $rating_stat['criteria_info'] ) ? $rating_stat['criteria_info'] : [];
			$criteria_stat_scores = isset( $rating_stat['criteria_stat_scores'] ) ? $rating_stat['criteria_stat_scores'] : [];

			$criteria_infosT       = [];
			$criteria_stat_scoresT = [];

			if ( is_array( $ratings_stars ) && sizeof( $ratings_stars ) > 0 ) {
				foreach ( $ratings_stars as $criteria_id => $ratings_star ) {

					$rating_score      = isset( $ratings_star['score_standard'] ) ? $ratings_star['score_standard'] : 0; //score in 5
					$rating_score_ceil = ceil( $rating_score );

					//if(isset( $criteria_infos[ $criteria_id ] )){
					//criteria previous existed
					$criteria_info = isset( $criteria_infos[ $criteria_id ] ) ? $criteria_infos[ $criteria_id ] : [];

					$criteria_avg_rating  = isset( $criteria_info['avg_rating'] ) ? $criteria_info['avg_rating'] : 0;
					$criteria_total_count = isset( $criteria_info['total_count'] ) ? $criteria_info['total_count'] : 0;

					$criteria_total_score     = $criteria_avg_rating * $criteria_total_count;
					$criteria_total_count_new = $criteria_total_count - 1;

					if ( $criteria_total_count_new != 0 ) {
						$criteria_total_score_new = $criteria_total_score - $rating_score;
						$criteria_avg_rating_new  = number_format( ( $criteria_total_score_new / $criteria_total_count_new ), 2 );

						$criteria_infosT[ $criteria_id ] = [
							'avg_rating'  => $criteria_avg_rating_new,
							'total_count' => $criteria_total_count_new
						];

						$criteria_stat_score = isset( $criteria_stat_scores[ $criteria_id ] ) ? $criteria_stat_scores[ $criteria_id ] : [];
						if ( isset( $criteria_stat_score[ $rating_score_ceil ] ) ) {
							$new_count                                          = intval( $criteria_stat_score[ $rating_score_ceil ]['count'] ) - 1;
							$criteria_stat_score[ $rating_score_ceil ]['count'] = $new_count;
						}

						//calculate percentage once again
						foreach ( $criteria_stat_score as $score_loop => $count_percent ) {
							$criteria_stat_score[ $score_loop ]['percent'] = number_format( ( intval( $criteria_stat_score[ $score_loop ]['count'] ) / $criteria_total_count_new ) * 100, 2 );
						}

						$criteria_stat_scoresT[ $criteria_id ] = $criteria_stat_score;
					}
				}

				$rating_stat['criteria_info']        = $criteria_infosT;
				$rating_stat['criteria_stat_scores'] = $criteria_stat_scoresT;
			}//end calculate criteria based rating stat

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
			$process_status = $wpdb->update(
				$table_rating_avg,
				[
					'avg_rating'    => $score_new,
					'total_count'   => $total_count_new,
					'date_modified' => current_time( 'mysql' ),
					'rating_stat'   => maybe_serialize( $rating_stat )
				],
				[ 'id' => $post_avg_rating['id'] ],
				[
					'%f', //avg_rating
					'%d', //total_count
					'%s', //date_modified
					'%s', //rating_stat
				],
				[ '%d' ]
			);

			update_post_meta( $post_id, '_cbxmcratingreview_avg', $score_new ); //todo: should we ceil ?
			update_post_meta( $post_id, '_cbxmcratingreview_total', $total_count_new );
		} else {
			// as no entry for this post so delete the entry from avg table
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared , WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
			$process_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_rating_avg WHERE id=%d", intval( $post_avg_rating['id'] ) ) );

			//update_post_meta( $post_id, '_cbxmcratingreview_avg', 0 );
			//update_post_meta( $post_id, '_cbxmcratingreview_total', 0 );

			delete_post_meta( $post_id, '_cbxmcratingreview_avg' );
			delete_post_meta( $post_id, '_cbxmcratingreview_total' );
		}


		return $process_status;
	}//end method dateReadableFormat

/**
	 * If review rating changed in published status
	 *
	 * @param array $review_info
	 *
	 * @return false|int|null
	 */
	public static function editPostwAvg( $new_status, $review_info, $review_info_old ) {
		//if not publish status we can ignore
		if ( intval( $new_status ) != 1 ) {
			return;
		}

		$score     = $review_info['score'];
		$score_old = $review_info_old['score'];

		//if new score is same we can ignore
		//if ( $score == $score_old ) {return;} //todo: need to rethink


		global $wpdb;
		$table_rating_avg = $wpdb->prefix . 'cbxmcratingreview_log_avg';
		$settings         = new CBXMCRatingReviewSettings();


		$post_id = intval( $review_info['post_id'] );
		$form_id = intval( $review_info['form_id'] );

		$post_avg_rating = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );
		$rating_stat     = maybe_unserialize( $post_avg_rating['rating_stat'] );

		$avg_rating  = $post_avg_rating['avg_rating'];
		$total_count = $post_avg_rating['total_count'];
		$total_score = $avg_rating * $total_count;


		$ceil_rating_score     = ceil( $score );
		$ceil_rating_score_old = ceil( $score );

		// avg adjust
		$process_status = null;


		if ( $score != $score_old ) {
			$total_score_new = $total_score + $score - $score_old;
			$score_new       = number_format( ( $total_score_new / $total_count ), 2 );

			//rating score percentage calculation
			$rating_stat_scores = $rating_stat['rating_stat_scores'];

			//at first reduce old score count
			if ( isset( $rating_stat_scores[ $ceil_rating_score_old ] ) ) {
				$new_count                                             = intval( $rating_stat_scores[ $ceil_rating_score_old ]['count'] ) - 1;
				$rating_stat_scores[ $ceil_rating_score_old ]['count'] = $new_count;
			}

			//now add new score value
			if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
				$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) + 1;
				$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
			}

			//calculate percentage once again
			foreach ( $rating_stat_scores as $score_loop => $count_percent ) {
				$rating_stat_scores[ $score_loop ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score_loop ]['count'] ) / $total_count ) * 100, 2 );
			}

			$rating_stat['rating_stat_scores'] = $rating_stat_scores;
			//end rating score percentage calculation
		} else {
			$score_new = $score;
		}

		//calculate criteria based rating stat
		//here  we will take the ratings from the new rating value
		$ratings       = isset( $review_info['ratings'] ) ? maybe_unserialize( $review_info['ratings'] ) : [];
		$ratings_stars = isset( $ratings['ratings_stars'] ) ? maybe_unserialize( $ratings['ratings_stars'] ) : [];


		$ratings_old       = isset( $review_info_old['ratings'] ) ? maybe_unserialize( $review_info_old['ratings'] ) : [];
		$ratings_stars_old = isset( $ratings_old['ratings_stars'] ) ? maybe_unserialize( $ratings_old['ratings_stars'] ) : [];

		$criteria_infos       = isset( $rating_stat['criteria_info'] ) ? $rating_stat['criteria_info'] : [];
		$criteria_stat_scores = isset( $rating_stat['criteria_stat_scores'] ) ? $rating_stat['criteria_stat_scores'] : [];

		$criteria_infosT       = [];
		$criteria_stat_scoresT = [];


		if ( is_array( $ratings_stars ) && sizeof( $ratings_stars ) > 0 ) {
			foreach ( $ratings_stars as $criteria_id => $ratings_star ) {

				$ratings_star_old = isset( $ratings_stars_old[ $criteria_id ] ) ? $ratings_stars_old[ $criteria_id ] : [];

				$rating_score_avg     = isset( $ratings_star['score_standard'] ) ? $ratings_star['score_standard'] : 0;         //score in 5
				$rating_score_avg_old = isset( $ratings_star_old['score_standard'] ) ? $ratings_star_old['score_standard'] : 0; //score in 5

				$rating_score_avg_ceil     = ceil( $rating_score_avg );
				$rating_score_avg_old_ceil = ceil( $rating_score_avg_old );


				//for this specific criteria  user's rating is changed, so we need to take care
				if ( $rating_score_avg != $rating_score_avg_old ) {

					if ( isset( $criteria_infos[ $criteria_id ] ) ) {
						//criteria previously existed

						$criteria_info = $criteria_infos[ $criteria_id ];

						$criteria_avg_rating  = $criteria_info['avg_rating'];
						$criteria_total_count = $criteria_info['total_count'];

						$criteria_total_score     = $criteria_avg_rating * $criteria_total_count;
						$criteria_total_score_new = $criteria_total_score + $rating_score_avg - $rating_score_avg_old;
						$criteria_avg_rating_new  = number_format( ( $criteria_total_score_new / $criteria_total_count ), 2 );
						//$criteria_avg_rating_new_ceil = ceil( $criteria_avg_rating_new );


						$criteria_infosT[ $criteria_id ] = [
							'avg_rating'  => $criteria_avg_rating_new,
							'total_count' => $criteria_total_count
						];

						//re-calculate the percentage for star
						$criteria_stat_score = isset( $criteria_stat_scores[ $criteria_id ] ) ? $criteria_stat_scores[ $criteria_id ] : [];

						//first minus 1 from previous ceil count and add 1 for new ceil count, then converted to percentage
						if ( isset( $criteria_stat_score[ $rating_score_avg_old_ceil ] ) ) {
							$new_count                                                  = intval( $criteria_stat_score[ $rating_score_avg_old_ceil ]['count'] ) - 1;
							$criteria_stat_score[ $rating_score_avg_old_ceil ]['count'] = $new_count;
						}

						if ( isset( $criteria_stat_score[ $rating_score_avg_ceil ] ) ) {
							$new_count                                              = intval( $criteria_stat_score[ $rating_score_avg_ceil ]['count'] ) + 1;
							$criteria_stat_score[ $rating_score_avg_ceil ]['count'] = $new_count;
						} else {
							$criteria_stat_score[ $rating_score_avg_ceil ]['count'] = 1;
						}


						//calculate percentage once again
						foreach ( $criteria_stat_score as $score_loop => $count_percent ) {
							$criteria_stat_score[ $score_loop ]['percent'] = number_format( ( intval( $criteria_stat_score[ $score_loop ]['count'] ) / $criteria_total_count ) * 100, 2 );
						}

						$criteria_stat_scoresT[ $criteria_id ] = $criteria_stat_score;

					} else {
						//this criteria is first time rated


						$criteria_infosT[ $criteria_id ] = [
							'avg_rating'  => $rating_score_avg,
							'total_count' => 1
						];


						$criteria_stat_scoresT[ $criteria_id ][ $rating_score_avg_ceil ] = [
							'count'   => 1,
							'percent' => 100,
						];

					}

				}

			}

			$rating_stat['criteria_info']        = $criteria_infosT;
			$rating_stat['criteria_stat_scores'] = $criteria_stat_scoresT;
		}

		//end calculate criteria based rating stat

		$score = $score_new;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
		$process_status = $wpdb->update(
			$table_rating_avg,
			[
				'avg_rating'    => $score,
				'date_modified' => current_time( 'mysql' ),
				'rating_stat'   => maybe_serialize( $rating_stat )
			],
			[ 'id' => $post_avg_rating['id'] ],
			[
				'%f', //avg_rating
				'%s', //date_modified
				'%s', //rating_stat
			],
			[ '%d' ]
		);

		update_post_meta( $post_id, '_cbxmcratingreview_avg', $score_new ); //todo: should we use ceil ?

		return $process_status;
	}//end method allowedHtmlTags

/**
	 * @param $timestamp
	 *
	 * @return false|string
	 */
	public static function dateReadableFormat( $timestamp, $format = 'M j, Y' ) {
		$format = ( $format == '' ) ? 'M j, Y' : $format;

		return gmdate( $format, strtotime( $timestamp ) );
	}//end method most_rated_posts

/**
	 * HTML elements, attributes, and attribute values will occur in your output
	 * @return array
	 */
	public static function allowedHtmlTags() {
		$allowed_html_tags = [
			'a'      => [
				'href'  => [],
				'title' => [],
				//'class' => [],
				//'data'  => [],
				//'rel'   => [],
			],
			'br'     => [],
			'em'     => [],
			'ul'     => [//'class' => [],
			],
			'ol'     => [//'class' => [],
			],
			'li'     => [//'class' => [],
			],
			'strong' => [],
			'p'      => [
				//'class' => [],
				//'data'  => [],
				//'style' => [],
			],
			'span'   => [
				//					'class' => [],
				//'style' => [],
			],
		];

		return apply_filters( 'cbxmcratingreview_allowed_html_tags', $allowed_html_tags );
	}//end method lastest_ratings

	/**
	 * Get most rated posts
	 *
	 * @param int $form_id
	 * @param int $limit
	 * @param string $order_by
	 * @param string $order
	 * @param string $type
	 *
	 * @return array|null|object
	 */
	public static function most_rated_posts( $form_id = 0, $perpage = 10, $order_by = 'avg_rating', $order = 'DESC', $type = 'post' ) {
		global $wpdb;
		$table_posts          = $wpdb->prefix . 'posts';
		$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';

		$settings = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return null;
			} else {
				$form_id = $default_form;
			}
		}

		$join = $where_sql = $sql_select = '';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$where_sql .= $wpdb->prepare( ' avg_log.post_type=%s ', $type ); //" avg_log.post_type IN('".$post_types."')";


		if ( $where_sql != '' ) {
			$where_sql .= ' AND ';
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$where_sql .= $wpdb->prepare( ' avg_log.form_id = %d', $form_id );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}


		$sql_select = "SELECT avg_log.* FROM $table_rating_avg_log AS avg_log";

		$sorting_order = " ORDER BY $order_by $order ";

		$page        = 1;
		$start_point = ( $page * $perpage ) - $perpage;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $perpage;

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared , WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
		return $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );
	}//end method postAvgRatingRender

/**
	 * Latest ratings
	 *
	 * @param int $form_id
	 * @param int $perpage
	 * @param string $order_by
	 * @param string $order
	 * @param string $type
	 * @param int $user_id
	 *
	 * @return array|null|object
	 */
	public static function lastest_ratings( $form_id = 0, $perpage = 10, $order_by = 'id', $order = 'DESC', $type = 'post', $user_id = 0 ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';

		$settings = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return null;
			} else {
				$form_id = $default_form;
			}
		}

		$join = $where_sql = $sql_select = '';

		//$join = " LEFT JOIN $table_posts AS posts ON posts.ID = log.post_id ";
		//$join .= " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

		if ( $user_id !== 0 ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'user_id=%d', $user_id );
		}

		if ( $where_sql != '' ) {
			$where_sql .= ' AND ';
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$where_sql .= $wpdb->prepare( ' log.form_id = %d', $form_id );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$sql_select = "SELECT log.* FROM $table_rating_log AS log";

		$order_by      = 'id';
		$order         = 'DESC';
		$sorting_order = " ORDER BY $order_by $order ";

		$page = 1;

		$start_point = ( $page * $perpage ) - $perpage;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $perpage;

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared , WordPress.DB.DirectDatabaseQuery.DirectQuery , WordPress.DB.DirectDatabaseQuery.NoCaching
		return $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );
	}//end method postAvgDetailsRatingRender

/**
	 * Render single post avg rating for a form
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param bool $show_star
	 * @param bool $show_score
	 * @param bool $show_chart
	 *
	 * @return false|string
	 */
	public static function postAvgRatingRender( $form_id = 0, $post_id = 0, $show_star = true, $show_score = true, $show_chart = false ) {
		global $current_user;
		$ok_to_render = false;


		$avg_rating_html = '';

		$settings = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return $avg_rating_html;
			} else {
				$form_id = $default_form;
			}
		}

		$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


		$post_id   = intval( $post_id );
		$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = [ 'guest' ];
		} else {
			$userRoles = $current_user->roles;
		}


		//check if post type supported
		//$post_types_supported = $settings->get_field( 'post_types', 'cbxmcratingreview_common_config', [] );

		$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : [];


		//check if post type is supported
		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end post type support check


		//check if user role supported
		if ( $ok_to_render ) {
			//$user_roles_rate = $settings->get_field( 'user_roles_view', 'cbxmcratingreview_common_config', [] );
			$user_roles_rate = isset( $form['user_roles_view'] ) ? $form['user_roles_view'] : [];

			if ( ! is_array( $user_roles_rate ) ) {
				$user_roles_rate = [];
			}

			$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
			if ( sizeof( $intersectedRoles ) == 0 ) {
				$ok_to_render = false;
			}
		}
		//end user role checking


		if ( $ok_to_render ) {
			cbxmcratingreview_AddJsCss();

			$avg_rating_info = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );

			$avg_rating_html = cbxmcratingreview_get_template_html( 'rating-review-avg-rating.php', [
					'settings'        => $settings,
					'avg_rating_info' => $avg_rating_info,
					'show_star'       => $show_star,
					'show_score'      => $show_score,
					'show_chart'      => $show_chart
				]
			);

			return $avg_rating_html;
		}

		return $avg_rating_html;
	}//end method reviewformRender

/**
	 * Render single post details avg rating for a form
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param bool $show_star
	 * @param bool $show_score
	 * @param bool $show_chart
	 *
	 * @return false|string
	 */
	public static function postAvgDetailsRatingRender( $form_id = 0, $post_id = 0, $show_star = true, $show_score = true, $show_short = true, $show_chart = true ) {
		global $current_user;
		$ok_to_render = false;

		$avg_rating_html = '';
		$settings        = new CBXMCRatingReviewSettings();

		if ( $form_id == 0 ) {
			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return $avg_rating_html;
			} else {
				$form_id = $default_form;
			}
		}

		$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


		$post_id   = intval( $post_id );
		$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = [ 'guest' ];
		} else {
			$userRoles = $current_user->roles;
		}


		//check if post type supported
		//$post_types_supported = $settings->get_field( 'post_types', 'cbxmcratingreview_common_config', [] );

		$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : [];


		//check if post type is supported
		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end post type support check


		//check if user role supported
		if ( $ok_to_render ) {
			//$user_roles_rate = $settings->get_field( 'user_roles_view', 'cbxmcratingreview_common_config', [] );
			$user_roles_rate = isset( $form['user_roles_view'] ) ? $form['user_roles_view'] : [];

			if ( ! is_array( $user_roles_rate ) ) {
				$user_roles_rate = [];
			}

			$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
			if ( sizeof( $intersectedRoles ) == 0 ) {
				$ok_to_render = false;
			}
		}
		//end user role checking


		if ( $ok_to_render ) {
			cbxmcratingreview_AddJsCss();


			$avg_rating_info = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );

			$avg_rating_html = cbxmcratingreview_get_template_html( 'rating-review-details-avg-rating.php', [
					'settings'        => $settings,
					'avg_rating_info' => $avg_rating_info,
					'show_star'       => $show_star,
					'show_score'      => $show_score,
					'show_short'      => $show_short,
					'show_chart'      => $show_chart
				]
			);

			return $avg_rating_html;
		}

		return $avg_rating_html;
	}//end method utf8_compatible_length_check

	/**
	 * Render rating form
	 *
	 * @param int $form_id
	 * @param int $post_id
	 *
	 * @return string
	 */
	public static function reviewformRender( $form_id = 0, $post_id = 0 ) {
		global $current_user;
		$ok_to_render = false;

		$rating_form_html = '';

		$post_id   = absint( $post_id );
		$post_id   = ( $post_id == 0 ) ? absint( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = [ 'guest' ];
		} else {
			$userRoles = $current_user->roles;
		}

		$settings = new CBXMCRatingReviewSettings();
		if ( $form_id == 0 ) {
			$default_form = absint( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
			if ( $default_form == 0 ) {
				return $rating_form_html;
			} else {
				$form_id = $default_form;
			}
		}


		$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


		//check if post type supported
		$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : [];


		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end checking post types

		//check if user role supported
		if ( $ok_to_render ) {
			//$user_roles_rate = $settings->get_field( 'user_roles_rate', 'cbxmcratingreview_common_config', [] );
			$user_roles_rate = isset( $form['user_roles_rate'] ) ? $form['user_roles_rate'] : [];

			if ( ! is_array( $user_roles_rate ) ) {
				$user_roles_rate = [];
			}


			$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
			if ( sizeof( $intersectedRoles ) == 0 ) {
				$ok_to_render = false;
			}
		}
		//end checking user role support

		//now check if the user rated before
		if ( $ok_to_render == true ) {

			$user_rated_before = cbxmcratingreview_isPostRatedByUser( $form_id, $post_id, $user_id );

			if ( $user_rated_before ) {
				$ok_to_render = false;
			}

			//still put option if we want to allow repeat review
			$ok_to_render = apply_filters( 'cbxmcratingreview_allow_repeat_review', $ok_to_render, $user_rated_before, $user_id, $post_id, $form_id );
		}


		if ( apply_filters( 'cbxmcratingreview_render', $ok_to_render, $post_id, $post_type ) ) {
			cbxmcratingreview_AddJsCss();
			cbxmcratingreview_AddRatingFormJsCss();


			return cbxmcratingreview_get_template_html( 'rating-review-form.php', [
					'settings' => $settings,
					'post_id'  => $post_id,
					'form_id'  => $form_id,
					'form'     => $form
				]
			);
		}

		return $rating_form_html;
	}//end method setup_admin_postdata

/**
	 * Char Length check  thinking utf8 in mind
	 *
	 * @param $text
	 *
	 * @return int
	 */
	public static function utf8_compatible_length_check( $text ) {
		if ( seems_utf8( $text ) ) {
			$length = mb_strlen( $text );
		} else {
			$length = strlen( $text );
		}

		return $length;
	}//end method wp_reset_admin_postdata

	/**
	 * Setup a post object and store the original loop item so we can reset it later
	 *
	 * @param obj $post_to_setup The post that we want to use from our custom loop
	 */
	public static function setup_admin_postdata( $post_to_setup ) {

		//only on the admin side
		if ( is_admin() ) {

			//get the post for both setup_postdata() and to be cached
			global $post;

			//only cache $post the first time through the loop
			if ( ! isset( $GLOBALS['post_cache'] ) ) {
				$GLOBALS['post_cache'] = $post;
			}

			//setup the post data as usual
			$post = $post_to_setup;
			setup_postdata( $post );
		} else {
			setup_postdata( $post_to_setup );
		}
	}//end method plugin_action_links

	/**
	 * Reset $post back to the original item
	 *
	 */
	public static function wp_reset_admin_postdata() {

		//only on the admin and if post_cache is set
		if ( is_admin() && ! empty( $GLOBALS['post_cache'] ) ) {

			//globalize post as usual
			global $post;

			//set $post back to the cached version and set it up
			$post = $GLOBALS['post_cache'];
			setup_postdata( $post );

			//cleanup
			unset( $GLOBALS['post_cache'] );
		} else {
			wp_reset_postdata();
		}
	}//end method get_pages

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return  array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = [
			'settings' => '<a href="' . admin_url( 'admin.php?page=cbxmcratingreview-settings' ) . '" aria-label="' . esc_attr__( 'View settings', 'cbxmcratingreview' ) . '">' . esc_html__( 'Settings', 'cbxmcratingreview' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}//end method reviewToolbarRender

	/**
	 * Get all the pages
	 *
	 * @param $show_empty
	 *
	 * @return array
	 */
	public static function get_pages( $show_empty = false ) {
		$pages         = get_pages();
		$pages_options = [];

		if ( $show_empty ) {
			$pages_options[0] = esc_html__( 'Select page', 'cbxmcratingreview' );
		}

		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}

		return $pages_options;
	}//end method reviewReportButtonRender

	/**
	 * Render review toolbar
	 *
	 * @param $post_review
	 *
	 * @return string
	 */
	public static function reviewToolbarRender( $post_review ) {
		$rating_review_toolbar_html = '';

		$rating_review_toolbar_html .= cbxmcratingreview_get_template_html( 'rating-review-reviews-list-item-toolbar.php', [
				'post_review' => $post_review,
			]
		);

		return $rating_review_toolbar_html;
	}//end method form_default_criterias

	/**
	 * render single review delete button
	 *
	 * @param array $post_review
	 *
	 * @return string
	 */
	public static function reviewDeleteButtonRender( $post_review = [] ) {
		cbxmcratingreview_AddJsCss();

		$report_form_html = '';
		if ( is_array( $post_review ) && sizeof( $post_review ) > 0 ) {

			$report_form_html = cbxmcratingreview_get_template_html( 'rating-review-review-delete-button.php', [
					'post_review' => $post_review,
				]
			);
		}

		return $report_form_html;
	}//end method form_default_questions

	/**
	 * Question formats
	 *
	 * @return mixed|void
	 */
	public static function form_question_formats() {
		$form_question_formats = [
			'text'          => [
				'title'           => esc_html__( 'Sample Single line Question', 'cbxmcratingreview' ),
				'required'        => 0,
				'enabled'         => 1,
				'placeholder'     => esc_html__( 'Write here', 'cbxmcratingreview' ),
				'type'            => 'text',
				'public_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'public_display_text_field'
				],
				'answer_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'answer_display_text_field'
				]
			],
			'textarea'      => [
				'title'           => esc_html__( 'Sample Multiline Question', 'cbxmcratingreview' ),
				'required'        => 0,
				'enabled'         => 1,
				'placeholder'     => esc_html__( 'Write here', 'cbxmcratingreview' ),
				'type'            => 'textarea',
				'public_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'public_display_textarea_field'
				],
				'answer_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'answer_display_textarea_field'
				]
			],
			'number'        => [
				'title'           => esc_html__( 'Sample Number Field Question', 'cbxmcratingreview' ),
				'required'        => 0,
				'enabled'         => 1,
				'placeholder'     => esc_html__( 'Write here', 'cbxmcratingreview' ),
				'min'             => 0,
				'max'             => 100,
				'step'            => 1,
				'type'            => 'number',
				'public_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'public_display_number_field'
				],
				'answer_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'answer_display_number_field'
				]
			],
			'checkbox'      => [
				'title'           => esc_html__( 'Sample Checkbox Question', 'cbxmcratingreview' ),
				'required'        => 0,
				'enabled'         => 1,
				'type'            => 'checkbox',
				'public_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'public_display_checkbox_field'
				],
				'answer_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'answer_display_checkbox_field'
				]
			],
			'multicheckbox' => [
				'title'           => esc_html__( 'Sample Multi Checkbox Question', 'cbxmcratingreview' ),
				'required'        => 0,
				'enabled'         => 1,
				'type'            => 'multicheckbox',
				'last_count'      => 5,
				'options'         => [
					'0' => [ 'text' => esc_html__( 'Option 0', 'cbxmcratingreview' ) ],
					'1' => [ 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ],
					'2' => [ 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ],
					'3' => [ 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ],
					'4' => [ 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ]
				],
				'public_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'public_display_multicheckbox_field'
				],
				'answer_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'answer_display_multicheckbox_field'
				]
			],
			'radio'         => [
				'title'           => esc_html__( 'Sample Radio Question', 'cbxmcratingreview' ),
				'required'        => 0,
				'enabled'         => 1,
				'type'            => 'radio',
				'last_count'      => 5,
				'options'         => [
					'0' => [ 'text' => esc_html__( 'Option 0', 'cbxmcratingreview' ) ],
					'1' => [ 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ],
					'2' => [ 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ],
					'3' => [ 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ],
					'4' => [ 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ]
				],
				'public_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'public_display_radio_field'
				],
				'answer_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'answer_display_radio_field'
				]
			],
			'select'        => [
				'title'           => esc_html__( 'Sample Select Question', 'cbxmcratingreview' ),
				'required'        => 0,
				'enabled'         => 1,
				'multiple'        => 0,
				//0/1 for multiple enable disable
				'type'            => 'select',
				'last_count'      => 5,
				'options'         => [
					'0' => [ 'text' => esc_html__( 'Option 0', 'cbxmcratingreview' ) ],
					'1' => [ 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ],
					'2' => [ 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ],
					'3' => [ 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ],
					'4' => [ 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ]
				],
				'public_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'public_display_select_field'
				],
				'answer_renderer' => [
					'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
					'answer_display_select_field'
				]
			]
		];

		return apply_filters( 'cbxmcratingreview_form_question_formats', $form_question_formats );
	}//end method form_question_format

	/**
	 * Rating form question field types
	 *
	 * @return mixed|void
	 */
	public static function question_field_types() {
		$fieldTypes = [
			'text'          => esc_html__( 'Text', 'cbxmcratingreview' ),
			'textarea'      => esc_html__( 'Textarea', 'cbxmcratingreview' ),
			'number'        => esc_html__( 'Number', 'cbxmcratingreview' ),
			'radio'         => esc_html__( 'Radio', 'cbxmcratingreview' ),
			'select'        => esc_html__( 'Dropdown', 'cbxmcratingreview' ),
			'checkbox'      => esc_html__( 'Checkbox', 'cbxmcratingreview' ),
			'multicheckbox' => esc_html__( 'Multi Checkbox', 'cbxmcratingreview' )
		];

		return apply_filters( 'cbxmcratingreview_question_field_types', $fieldTypes );
	}//end method form_field_types

/**
	 * Returns Plain rating form lists with title and id as associative array
	 *
	 * @return array|null
	 */
	public static function getRatingFormsList() {
		try {
			$settings = new CBXMCRatingReviewSettings();

			$forms = RatingReviewForm::orderBy( 'name', 'asc' )->get()->toArray();

			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

			$rating_forms = [];

			if ( $forms !== null ) {
				foreach ( $forms as $form ) {
					$form_id    = intval( $form['id'] );
					$form_title = esc_attr( $form['name'] );

					if ( $form_title == '' ) {
						$form_title = esc_html__( 'Untitled Form', 'cbxmcratingreview' );
					}

					if ( $form_id == $default_form ) {
						$form_title .= ' ' . esc_html__( '(Default)', 'cbxmcratingreview' );
					}

					$rating_forms[ $form_id ] = $form_title;
				}
			}

			return $rating_forms;
		} catch ( QueryException $e ) {
			// Check if the error is due to a missing table
			if ( str_contains( $e->getMessage(), 'Base table or view not found' ) ) {
				//error_log( esc_html__( 'Form table does not exist. Please check the database structure.', 'cbxmcratingreview' ) );

				return [];
			}
		} catch ( Exception $e ) {
			return [];
		}
	}//end method form_default_fields

	/**
	 * Get all rating forms
	 *
	 *
	 * @return array|null
	 */
	public static function getRatingForms() {
		try {
			global $wpdb;

			$settings = new CBXMCRatingReviewSettings();

			$form_default = CBXMCRatingReviewHelper::form_default_fields();

			$default_form = intval( $settings->get_field( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

			$results = RatingReviewForm::orderBy( 'name', 'asc' )->get()->toArray();

			if ( empty( $results ) ) {
				return [];
			}

			$count = sizeof( $results );

			for ( $i = 0; $i < $count; $i ++ ) {

				$form_id    = intval( $results[ $i ]['id'] );
				$form_title = esc_attr( $results[ $i ]['name'] );
				if ( $form_title == '' ) {
					$form_title = esc_html__( 'Untitled Form', 'cbxmcratingreview' );
				}

				if ( $form_id == $default_form ) {
					$form_title            .= ' ' . esc_html__( '(Default)', 'cbxmcratingreview' );
					$results[ $i ]['name'] = $form_title;
				}

				$results[ $i ]["custom_criteria"] = maybe_unserialize( $results[ $i ]["custom_criteria"] );
				$results[ $i ]["custom_question"] = maybe_unserialize( $results[ $i ]["custom_question"] );

				$result       = $results[ $i ];
				$extra_fields = maybe_unserialize( $result['extrafields'] );

				$extra_fields = (array) $extra_fields;


				$result = array_merge( $result, $extra_fields );


				foreach ( $form_default as $key => $field ) {
					if ( $field['type'] == 'select' && isset( $field['multiple'] ) && $field['multiple'] == true ) {

						if ( isset( $result[ $key ] ) ) {
							$result[ $key ] = maybe_unserialize( $result[ $key ] ); // warning for new field
						} else {
							$result[ $key ] = $field['default']; // warning for new field
						}
					}
				}

				$custom_criterias = $result["custom_criteria"];
				foreach ( $custom_criterias as $criteria_index => $custom_criteria ) {
					$criteria_id     = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $criteria_index );
					$stars           = isset( $custom_criteria['stars'] ) ? $custom_criteria['stars'] : [];
					$stars_formatted = [];
					if ( is_array( $stars ) && sizeof( $stars ) > 0 ) {
						foreach ( $stars as $star_index => $star ) {

							/* translators: %d: star count  */
							$title = isset( $star['title'] ) ? esc_attr( $star['title'] ) : sprintf( esc_html__( 'Star %d', 'cbxmcratingreview' ), ( $star_index + 1 ) );

							$stars_formatted[ $star_index ] = $title;

						}
					}

					$stars_length = sizeof( $stars_formatted );

					$stars_summary           = [];
					$stars_summary['length'] = intval( $stars_length );
					$stars_summary['stars']  = $stars_formatted;

					$custom_criterias[ $criteria_index ]['stars_formatted'] = $stars_summary;

				}

				$result["custom_criteria"] = $custom_criterias;

				$results[ $i ] = $result;

			}

			return apply_filters( 'cbxmcratingreview_get_ratingForms', $results );
		} catch ( QueryException $e ) {
			// Check if the error is due to a missing table
			if ( str_contains( $e->getMessage(), 'Base table or view not found' ) ) {
				//error_log( esc_html__( 'Form table does not exist. Please check the database structure.', 'cbxmcratingreview' ) );

				return [];
			}
		} catch ( Exception $e ) {
			return [];
		}

	}//end method form_default_extra_fields

	/**
	 * Returns rating form count
	 *
	 * @return int
	 */
	public static function getRatingForms_Count() {
		return RatingReviewForm::count();
	}//end method getRatingFormsList

	/**
	 * Possible sort orders
	 *
	 * @return string[]
	 */
	public static function sort_orders() {
		return [ 'ASC', 'DESC' ];
	}//end method getRatingForms

	/**
	 * Possible order by for reviews
	 *
	 * @return void
	 */
	public static function reviews_order_bys() {
		return [ 'id' ];
	}//end method get_ratingForm

	/**
	 * get monthly review count
	 *
	 */
	public static function getMonthlyReviewCounts( $year = null ) {
		// Initialize array with month names and count set to 0
		$monthly_counts = array_fill_keys(
			[ 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec' ],
			0
		);

		$year = intval( $year ) ?: intval( gmdate( 'Y' ) ); // Default to current year if $year is null

		if ( $year > 0 ) {
			try {

				// Build the query using Eloquent
				$results = RatingReviewLog::selectRaw( 'MONTH(date_created) as month, COUNT(*) as count' )
				                          ->whereYear( 'date_created', $year )
				                          ->groupByRaw( 'MONTH(date_created)' )->get();

				// Populate the monthly_counts array with the results
				foreach ( $results as $result ) {
					$month_number                  = intval( $result->month );
					$month_name                    = strtolower( gmdate( 'M', mktime( 0, 0, 0, $month_number, 10 ) ) );
					$monthly_counts[ $month_name ] = intval( $result->count );
				}

			} catch ( Exception ) {
				return $monthly_counts;
			}
		}

		return $monthly_counts;
	}//end method get_ratingForms_Count

	/**
	 * Get single review review count for each day of the current week
	 */
	public static function getWeeklyReviewCounts( $post_id = 0 ) {
		// Initialize array with day names and count set to 0
		$weekly_counts = array_fill_keys(
			[ 'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat' ],
			0
		);

		if ( $post_id > 0 ) {
			$results = RatingReviewLog::selectRaw( 'DAYOFWEEK(date_created) as day, COUNT(*) as count' )
			                          ->where( 'post_id', $post_id )
			                          ->whereRaw( 'YEARWEEK(date_created, 1) = YEARWEEK(CURDATE(), 1)' )
			                          ->groupByRaw( 'DAYOFWEEK(date_created)' )
			                          ->get();
		} else {
			$results = RatingReviewLog::selectRaw( 'DAYOFWEEK(date_created) as day, COUNT(*) as count' )
			                          ->whereRaw( 'YEARWEEK(date_created, 1) = YEARWEEK(CURDATE(), 1)' )
			                          ->groupByRaw( 'DAYOFWEEK(date_created)' )
			                          ->get();
		}

		// Populate the weekly_counts array with the results
		foreach ( $results as $result ) {
			$day_number                 = intval( $result->day ) - 1; // DAYOFWEEK returns 1 for Sunday, 2 for Monday, etc.
			$day_name                   = strtolower( gmdate( 'D', strtotime( "Sunday +{$day_number} days" ) ) );
			$weekly_counts[ $day_name ] = intval( $result->count );
		}

		return $weekly_counts;

	}//end method sort_orders

	/**
	 * Get total job counts with optional status grouping.
	 *
	 * @param bool $status
	 *
	 * @return array|int
	 */
	public static function getTotalReviewedPostCount() {
		try {
			$totalPost = RatingReviewLogAvg::count();

			return $totalPost;
		} catch ( Exception $e ) {
			return 0;
		}
	}//end method getTotalReviewedPostCount

	/**
	 * log builder js translation list
	 *
	 * @param $current_user
	 * @param $blog_id
	 *
	 * @return mixed|void
	 */
	public static function cbxmcratingreview_log_builder_js_translation( $current_user, $blog_id ) {
		$common_js_translations = self::common_js_translation( $current_user, $blog_id );

		$user_roles_no_guest   = self::user_roles( true, false );
		$user_roles_with_guest = self::user_roles( true, true );

		$form_default_criterias = self::form_default_criterias();
		$form_default_questions = self::form_default_questions();

		$form_js_translations = [
			'post_types'             => self::post_types( true ),
			'user_roles_no_guest'    => $user_roles_no_guest,
			'user_roles_with_guest'  => $user_roles_with_guest,
			'form_default_criterias' => $form_default_criterias,
			'form_default_questions' => $form_default_questions,
			'review_statuses'        => self::ReviewStatusOptions(),
			'form_list'              => self::form_list(),
			'translations'           => [
				'post_types'                           => esc_html__( 'Post Types', 'cbxmcratingreview' ),
				'edit_review'                          => esc_html__( 'Edit Review', 'cbxmcratingreview' ),
				'review'                               => esc_html__( 'Review', 'cbxmcratingreview' ),
				'add_new'                              => esc_html__( 'Add New', 'cbxmcratingreview' ),
				'reviewed_by'                          => esc_html__( 'Reviewed By', 'cbxmcratingreview' ),
				'created'                              => esc_html__( 'Created', 'cbxmcratingreview' ),
				'last_update'                          => esc_html__( 'Last Update', 'cbxmcratingreview' ),
				'last_update_by'                       => esc_html__( 'Last Update By', 'cbxmcratingreview' ),
				'one_line_review'                      => esc_html__( 'One line review', 'cbxmcratingreview' ),
				'your_review'                          => esc_html__( 'Your Review', 'cbxmcratingreview' ),
				'edit_your_review'                     => esc_html__( 'Edit Your Rating Experience', 'cbxmcratingreview' ),
				'questions_and_answers'                => esc_html__( 'Questions and Answers', 'cbxmcratingreview' ),
				'name'                                 => esc_html__( 'Name', 'cbxmcratingreview' ),
				'form'                                 => esc_html__( 'Form', 'cbxmcratingreview' ),
				'post'                                 => esc_html__( 'Post', 'cbxmcratingreview' ),
				'user'                                 => esc_html__( 'User', 'cbxmcratingreview' ),
				'log'                                  => esc_html__( 'Log', 'cbxmcratingreview' ),
				'label'                                => esc_html__( 'Label', 'cbxmcratingreview' ),
				'value'                                => esc_html__( 'Value', 'cbxmcratingreview' ),
				'score'                                => esc_html__( 'Score', 'cbxmcratingreview' ),
				'headline'                             => esc_html__( 'Headline', 'cbxmcratingreview' ),
				'comment'                              => esc_html__( 'Comment', 'cbxmcratingreview' ),
				'edit'                                 => esc_html__( 'Edit', 'cbxmcratingreview' ),
				'options'                              => esc_html__( 'Options', 'cbxmcratingreview' ),
				'forms'                                => esc_html__( 'Forms', 'cbxmcratingreview' ),
				'logs'                                 => esc_html__( 'Logs', 'cbxmcratingreview' ),
				'avg_logs'                             => esc_html__( 'Log Average', 'cbxmcratingreview' ),
				'title'                                => esc_html__( 'Title', 'cbxmcratingreview' ),
				'subject'                              => esc_html__( 'Subject', 'cbxmcratingreview' ),
				'search_text'                          => esc_html__( 'Search', 'cbxmcratingreview' ),
				'update'                               => esc_html__( 'Update', 'cbxmcratingreview' ),
				'close'                                => esc_html__( 'Close', 'cbxmcratingreview' ),
				'type'                                 => esc_html__( 'Type', 'cbxmcratingreview' ),
				'datetime'                             => esc_html__( 'DateTime', 'cbxmcratingreview' ),
				'time'                                 => esc_html__( 'Time', 'cbxmcratingreview' ),
				'date'                                 => esc_html__( 'Date', 'cbxmcratingreview' ),
				'description'                          => esc_html__( 'Description', 'cbxmcratingreview' ),
				'placeholder'                          => esc_html__( 'Placeholder', 'cbxmcratingreview' ),
				'app_heading'                          => htmlspecialchars_decode( esc_html__( 'Rating & Review : Review Manager', 'cbxmcratingreview' ) ),
				'log_manager'                          => esc_html__( 'Rating Log Manager', 'cbxmcratingreview' ),
				'avg_log_manager'                      => htmlspecialchars_decode( esc_html__( 'Rating & Review : Average Log Manager', 'cbxmcratingreview' ) ),
				'no_log_found'                         => esc_html__( 'No Log found', 'cbxmcratingreview' ),
				'n_a'                                  => esc_html__( 'N\A', 'cbxmcratingreview' ),
				'date_range'                           => esc_html__( 'Date Range', 'cbxmcratingreview' ),
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
				'modified'                             => esc_html__( 'Modified', 'cbxmcratingreview' ),
				'article'                              => esc_html__( 'Article', 'cbxmcratingreview' ),
				'view_all'                             => esc_html__( 'View All', 'cbxmcratingreview' ),
				'average'                              => esc_html__( 'Average', 'cbxmcratingreview' ),
			],
		];

		$js_translations = array_merge_recursive( $common_js_translations, $form_js_translations );

		return apply_filters( 'cbxmcratingreview_log_js_translation', $js_translations );
	}//end function getMonthlyReviewCounts

	/**
	 * common js translation
	 *
	 * @param string $current_user
	 * @param string $blog_id
	 *
	 * @return mixed|void
	 * @since 1.0.0
	 */
	public static function common_js_translation( $current_user = '', $blog_id = '' ) {
		$current_user = ( $current_user == '' ) ? wp_get_current_user() : $current_user;

		if ( $blog_id == '' ) {
			$blog_id = is_multisite() ? get_current_blog_id() : null;
		}

		$js_translations = [
			'icons_url'                   => CBXMCRATINGREVIEW_ROOT_URL . '/assets/icons/',
			'nonce'                       => wp_create_nonce( 'cbxmcratingreview' ),
			'rest_nonce'                  => wp_create_nonce( 'wp_rest' ),
			'dashboard_menus'             => CBXMCRatingReviewAdminHelper::dashboard_menus(),
			'site_url'                    => site_url(),
			'admin_url'                   => admin_url(),
			'translations'                => [
				'buttons'                   => [
					'close'  => [
						'title'    => esc_attr__( 'Click to close', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'Close', 'cbxmcratingreview' )
					],
					'search' => [
						'title'    => esc_attr__( 'Click to search', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'Search', 'cbxmcratingreview' )
					],
					'reset'  => [
						'title'    => esc_attr__( 'Click to reset', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'Reset', 'cbxmcratingreview' )
					],
					'filter' => [
						'title'    => esc_attr__( 'Column Filter', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'Filter', 'cbxmcratingreview' )
					],
					'view'   => [
						'title'    => esc_attr__( 'Click to view', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'View', 'cbxmcratingreview' )
					],
					'clone'  => [
						'title'    => esc_attr__( 'Click to clone', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'Clone', 'cbxmcratingreview' )
					],
					'edit'   => [
						'title'    => esc_attr__( 'Click to edit', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'Edit', 'cbxmcratingreview' )
					],
					'delete' => [
						'title'    => esc_attr__( 'Click to delete', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'Delete', 'cbxmcratingreview' )
					],
					'gear'   => [
						'title'    => esc_attr__( 'Click to Configure', 'cbxmcratingreview' ),
						'sr_label' => esc_html__( 'Configure', 'cbxmcratingreview' )
					],
				],
				'select_status'             => esc_html__( 'Select status', 'cbxmcratingreview' ),
				'select_form'               => esc_html__( 'Select form', 'cbxmcratingreview' ),
				'delete_all'                => esc_html__( 'Delete All', 'cbxmcratingreview' ),
				'total'                     => esc_html__( 'Total', 'cbxmcratingreview' ),
				'id'                        => esc_html__( 'ID', 'cbxmcratingreview' ),
				'action'                    => esc_html__( 'Action', 'cbxmcratingreview' ),
				'delete_confirmation_title' => esc_html__( 'Are you sure?', 'cbxmcratingreview' ),
				'delete_confirmation_txt'   => esc_html__( 'You want to delete this.', 'cbxmcratingreview' ),
				'delete_btn_txt'            => esc_html__( 'Delete', 'cbxmcratingreview' ),
				'showing'                   => esc_html__( 'Showing ', 'cbxmcratingreview' ),
				'of'                        => esc_html__( 'of', 'cbxmcratingreview' ),
				'rowCount'                  => esc_html__( 'Row count ', 'cbxmcratingreview' ),
				'goTo'                      => esc_html__( 'Go to page ', 'cbxmcratingreview' ),
				'delete'                    => esc_html__( 'Delete', 'cbxmcratingreview' ),
				'status'                    => esc_html__( 'Status', 'cbxmcratingreview' ),
				'attachment'                => esc_html__( 'Attachment', 'cbxmcratingreview' ),
				'upload'                    => esc_html__( 'Upload', 'cbxmcratingreview' ),
				'posted'                    => esc_html__( 'Posted', 'cbxmcratingreview' ),
				'back'                      => esc_html__( 'Back', 'cbxmcratingreview' ),
				'save'                      => esc_html__( 'Save', 'cbxmcratingreview' ),
				'thumbs_up'                 => esc_html__( 'Thumbs Up', 'cbxmcratingreview' ),
				'thumbs_down'               => esc_html__( 'Thumbs Down', 'cbxmcratingreview' ),
			],
			'rest_end_points'             => [
				'get_form'      => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/form-data' ) ),
				'save_form'     => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/save-form' ) ),
				'get_form_list' => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/form-list' ) ),
				'delete_form'   => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/delete-form' ) ),

				'get_log'      => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/log-data' ) ),
				'save_log'     => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/save-log' ) ),
				'get_log_list' => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/log-list' ) ),
				'delete_log'   => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/delete-log' ) ),

				'get_user_log'      => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/user-log-data' ) ),
				'save_user_log'     => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/save-user-log' ) ),
				'get_user_log_list' => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/user-log-list' ) ),

				'get_avg_log_list' => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/avg-log-list' ) ),
				'delete_avg_log'   => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/delete-avg-log' ) ),

				'reset_option'       => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/reset-option' ) ),
				'migrate_table'      => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/migrate-table' ) ),
				'dashboard_overview' => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/dashboard-overview' ) ),
				'dashboard_listing'  => esc_url_raw( get_rest_url( $blog_id, 'cbxmcratingreview/v1/dashboard-listing' ) ),
			],
			'cbx_table_lite'              => self::table_light_translation(),
			'cbxmcratingreviewpro_active' => cbxmcratingreview_check_pro_plugin_active(),
		];

		return $js_translations;
	}// end function common_js_translation

	/**
	 * dashboard menu list
	 *
	 * @since 1.0.0
	 */
	public static function dashboard_menus() {
		$menus = [];

		//dashboard
		if ( current_user_can( 'cbxmcratingreview_dashboard_manage' ) ) {
			$menus['cbxmcratingreview-dashboard'] = [
				'url'   => admin_url( 'admin.php?page=cbxmcratingreview-dashboard' ),
				'title' => htmlspecialchars_decode( esc_attr__( 'Rating & Review : Dashboard', 'cbxmcratingreview' ) ),
				'label' => htmlspecialchars_decode( esc_html__( 'Dashboard', 'cbxmcratingreview' ) ),
			];
		}

		//log manager
		if ( current_user_can( 'cbxmcratingreview_log_manage' ) ) {
			$menus['cbxmcratingreviewreview-list'] = [
				'url'   => admin_url( 'admin.php?page=cbxmcratingreviewreview-list' ),
				'title' => htmlspecialchars_decode( esc_attr__( 'Rating & Review : Review Manager', 'cbxmcratingreview' ) ),
				'label' => htmlspecialchars_decode( esc_html__( 'Review Manager', 'cbxmcratingreview' ) ),
			];
		}

		//avg manager
		if ( current_user_can( 'cbxmcratingreview_log_manage' ) ) {
			$menus['cbxmcratingreviewrating-avg-list'] = [
				'url'   => admin_url( 'admin.php?page=cbxmcratingreviewrating-avg-list' ),
				'title' => htmlspecialchars_decode( esc_attr__( 'Rating & Review : Average Log Manager', 'cbxmcratingreview' ) ),
				'label' => htmlspecialchars_decode( esc_html__( 'Average Log Manager', 'cbxmcratingreview' ) ),
			];
		}

		//form manager
		if ( current_user_can( 'cbxmcratingreview_form_manage' ) ) {
			$menus['cbxmcratingreview-form'] = [
				'url'   => admin_url( 'admin.php?page=cbxmcratingreview-form' ),
				'title' => htmlspecialchars_decode( esc_attr__( 'Rating & Review : Rating Forms', 'cbxmcratingreview' ) ),
				'label' => htmlspecialchars_decode( esc_html__( 'Rating Forms', 'cbxmcratingreview' ) ),
			];
		}


		//settings
		if ( current_user_can( 'cbxmcratingreview_settings_manage' ) ) {
			$menus['cbxmcratingreview_settings'] = [
				'url'   => admin_url( 'admin.php?page=cbxmcratingreviewsettings' ),
				'title' => htmlspecialchars_decode( esc_attr__( 'Rating & Review : Global Settings', 'cbxmcratingreview' ) ),
				'label' => htmlspecialchars_decode( esc_html__( 'Global Settings', 'cbxmcratingreview' ) ),
			];
		}

		//emails
		if ( current_user_can( 'cbxmcratingreview_settings_manage' ) ) {
			$menus['cbxmcratingreview-emails'] = [
				'url'   => admin_url( 'admin.php?page=cbxmcratingreview-emails' ),
				'title' => htmlspecialchars_decode( esc_attr__( 'Rating & Review : Emails', 'cbxmcratingreview' ) ),
				'label' => htmlspecialchars_decode( esc_html__( 'Emails', 'cbxmcratingreview' ) ),
			];
		}

		//help and support
		if ( current_user_can( 'cbxmcratingreview_settings_manage' ) ) {
			$menus['cbxmcratingreview_support'] = [
				'url'   => admin_url( 'admin.php?page=cbxmcratingreview-help-support' ),
				'title' => htmlspecialchars_decode( esc_attr__( 'Rating & Review : Helps & Updates', 'cbxmcratingreview' ) ),
				'label' => htmlspecialchars_decode( esc_html__( 'Helps & Updates', 'cbxmcratingreview' ) ),
			];
		}

		return $menus;
	}//end method getTotalReviewedPostCount

	/**
	 * Translation for table translation
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function table_light_translation() {
		return [
			'loading' => esc_html__( 'Loading...', 'cbxmcratingreview' ),
			'first'   => esc_html__( 'First', 'cbxmcratingreview' ),
			'prev'    => esc_html__( 'Prev', 'cbxmcratingreview' ),
			'next'    => esc_html__( 'Next', 'cbxmcratingreview' ),
			'last'    => esc_html__( 'Last', 'cbxmcratingreview' )
		];
	} //end method table_light_translation

	/**
	 *  Default criteria and stars
	 *
	 * @return mixed|void
	 */
	public static function form_default_criterias() {
		$form_criteria = [
			'0' => [
				'label'       => esc_html__( 'Untitled criteria - 0', 'cbxmcratingreview' ),
				'criteria_id' => 0,
				'stars'       => [
					'0' => [
						'title' => esc_html__( 'Worst', 'cbxmcratingreview' )
					],
					'1' => [
						'title' => esc_html__( 'Bad', 'cbxmcratingreview' )
					],
					'2' => [
						'title' => esc_html__( 'Not Bad', 'cbxmcratingreview' )
					],
					'3' => [
						'title' => esc_html__( 'Good', 'cbxmcratingreview' )
					],
					'4' => [
						'title' => esc_html__( 'Best', 'cbxmcratingreview' )
					]
				]
			],
			'1' => [
				'label'       => esc_html__( 'Untitled criteria - 1', 'cbxmcratingreview' ),
				'criteria_id' => 1,
				'stars'       => [
					'0' => [
						'title' => esc_html__( 'Worst', 'cbxmcratingreview' )
					],
					'1' => [
						'title' => esc_html__( 'Bad', 'cbxmcratingreview' )
					],
					'2' => [
						'title' => esc_html__( 'Not Bad', 'cbxmcratingreview' )
					],
					'3' => [
						'title' => esc_html__( 'Good', 'cbxmcratingreview' )
					],
					'4' => [
						'title' => esc_html__( 'Best', 'cbxmcratingreview' )
					]
				]
			],
			'2' => [
				'label'       => esc_html__( 'Untitled criteria - 2', 'cbxmcratingreview' ),
				'criteria_id' => 2,
				'stars'       => [
					'0' => [
						'title' => esc_html__( 'Worst', 'cbxmcratingreview' )
					],
					'1' => [
						'title' => esc_html__( 'Bad', 'cbxmcratingreview' )
					],
					'2' => [
						'title' => esc_html__( 'Not Bad', 'cbxmcratingreview' )
					],
					'3' => [
						'title' => esc_html__( 'Good', 'cbxmcratingreview' )
					],
					'4' => [
						'title' => esc_html__( 'Best', 'cbxmcratingreview' )
					]
				]
			]
		];

		return apply_filters( 'cbxmcratingreview_form_default_criterias', $form_criteria );
	} //end method cbxmcratingreview_log_builder_js_translation

	/**
	 * Default questions
	 *
	 * @return mixed|void
	 */
	public static function form_default_questions() {
		$form_question = [
			'0' => [
				'title'       => esc_html__( 'Sample Question Title', 'cbxmcratingreview' ),
				'required'    => 0,
				'enabled'     => 0,
				'placeholder' => esc_html__( 'Write here', 'cbxmcratingreview' ),
				'type'        => 'text'
			],
			/*'1' => array(
					'title'    => esc_html__( 'Sample Question Title 1', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'placeholder'  => esc_html__('Write here', 'cbxmcratingreview'),
					'type'     => 'textarea'
				),
				'2' => array(
					'title'    => esc_html__( 'Sample Question Title 2', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'type'     => 'checkbox'
				),
				'3' => array(
					'title'    => esc_html__( 'Sample Question Title 3', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'type'     => 'multicheckbox',
					'last_count' => 5,
					'options'    => array(
						'0'         => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'1'         => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'2'         => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'3'         => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ),
						'4'         => array( 'text' => esc_html__( 'Option 5', 'cbxmcratingreview' ) )
					)
				),
				'4' => array(
					'title'    => esc_html__( 'Sample Question Title 4', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'type'     => 'radio',
					'last_count' => 5,
					'options'    => array(
						'0'         => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'1'         => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'2'         => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'3'         => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ),
						'4'         => array( 'text' => esc_html__( 'Option 5', 'cbxmcratingreview' ) )
					)
				),
				'5' => array(
					'title'    => esc_html__( 'Sample Question Title 5', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'multiple'  => 0, //0/1 for multiple enable disable
					'type'     => 'select',
					'last_count' => 5,
					'options'    => array(
						'0'         => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'1'         => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'2'         => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'3'         => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ),
						'4'         => array( 'text' => esc_html__( 'Option 5', 'cbxmcratingreview' ) )
					)
				),
				'6' => array(
					'title'    => esc_html__( 'Sample Question Title 6', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'placeholder'  => esc_html__('Write here', 'cbxmcratingreview'),
					'min'  => 0,
					'max'  => 100,
					'step'  => 1,
					'type'     => 'number'
				),*/
		];

		return apply_filters( 'cbxmcratingreview_form_default_questions', $form_question );
	} //end of method table_light_translation

	/**
	 * all posible status for a review
	 * @return array
	 */
	public static function ReviewStatusOptions() {
		$exprev_status_arr = [
			'-2' => esc_html__( 'Unverified', 'cbxmcratingreview' ),
			'-1' => esc_html__( 'Verified', 'cbxmcratingreview' ),
			'0'  => esc_html__( 'Pending', 'cbxmcratingreview' ),
			'1'  => esc_html__( 'Published', 'cbxmcratingreview' ),
			'2'  => esc_html__( 'Unpublished', 'cbxmcratingreview' ),
			'3'  => esc_html__( 'Spam', 'cbxmcratingreview' ),
		];

		return apply_filters( 'cbxmcratingreview_review_review_status_options', $exprev_status_arr );
	} //end of method form_list

	/**
	 * rating form list
	 *
	 * @since 1.0.0
	 */
	public static function form_list() {
		return RatingReviewForm::get()->pluck( 'name', 'id' );
	}//end function form_list

	/**
	 * get daily review count
	 *
	 */
	public static function getDailyReviewCounts( $year = null, $month = null ): array {
		$month = $month ? absint( $month ) : gmdate( 'm' );
		$year  = $year ? absint( $year ) : gmdate( 'Y' );

		// Initialize an array with days of the month, defaulting to 0 income
		$days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$daily_totals  = array_fill( 1, $days_in_month, 0 );

		try {
			if ( $year > 0 && $month > 0 ) {

				// Query the RatingReviewLog model for the specific month and year
				$query = RatingReviewLog::selectRaw( 'DAY(date_created) as day, COUNT(*) as count' )
				                        ->whereYear( 'date_created', $year )
				                        ->whereMonth( 'date_created', $month )
				                        ->groupByRaw( 'DAY(date_created)' );

				// Execute the query
				$results = $query->get();

				// Populate the daily_totals array with the results
				foreach ( $results as $result ) {
					$day_number                  = intval( $result->day );
					$daily_totals[ $day_number ] = intval( $result->count );
				}
			}
		} catch ( \Exception $e ) {

		}

		return $daily_totals;
	}//end method getDailyReviewCounts

	/**
	 * Get user display name
	 *
	 * @param null $user_id
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public static function userDisplayName( $user_id = null ) {
		$current_user      = $user_id ? new \WP_User( $user_id ) : wp_get_current_user();
		$user_display_name = $current_user->display_name;
		if ( $user_display_name != '' ) {
			return $user_display_name;
		}

		if ( isset( $current_user->first_name ) && $current_user->first_name ) {
			if ( isset( $current_user->last_name ) && $current_user->last_name ) {
				return $current_user->first_name . ' ' . $current_user->last_name;
			}

			return $current_user->first_name;
		}

		return esc_html__( 'Unnamed', 'cbxmcratingreview' );
	}//end method userDisplayNameAlt

	/**
	 * Get user display name alternative if display_name value is empty
	 *
	 * @param $current_user
	 * @param $user_display_name
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public static function userDisplayNameAlt( $current_user, $user_display_name = '' ) {
		if ( $user_display_name != '' ) {
			return $user_display_name;
		}

		if ( isset( $current_user->first_name ) && $current_user->first_name ) {
			if ( isset( $current_user->last_name ) && $current_user->last_name ) {
				return $current_user->first_name . ' ' . $current_user->last_name;
			}

			return $current_user->first_name;
		}

		return esc_html__( 'Unnamed', 'cbxmcratingreview' );
	}//end method migration_and_defaults

	/**
	 * On plugin activate
	 */
	public static function activate() {
		/*//check if can activate plugin
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$plugin = isset( $_REQUEST['plugin'] ) ? sanitize_text_field(wp_unslash($_REQUEST['plugin'])) : '';
		check_admin_referer( "activate-plugin_{$plugin}" );*/

		//set the current version
		update_option( 'cbxmcratingreview_version', CBXMCRATINGREVIEW_PLUGIN_VERSION );

		CBXMCRatingReviewHelper::migration_and_defaults();
		CBXMCRatingReviewHelper::create_pages();

		set_transient( 'cbxmcratingreview_activated_notice', 1 );
	}//end method activate

	/**
	 * On plugin activate run migration
	 */
	public static function migration_and_defaults() {
		MigrationManage::run();

		//set default data
		self::default_data_set();


	} //end method create_pages

	/**
	 * On plugin active or reset data set default data for this plugin
	 *
	 * @since 1.0.0
	 */
	public static function default_data_set() {

		// add role and custom capability
		self::defaultRoleCapability();

		// create default pages
		//$this->create_pages();
	}//end method create_page

	/**
	 * Create default role and capability on plugin activation and rest
	 *
	 * @since 1.0.0
	 */
	private static function defaultRoleCapability() {
		$caps = cbxmcratingreview_all_caps();

		//add the caps to the administrator role
		$role = get_role( 'administrator' );

		foreach ( $caps as $cap ) {
			//add cap to the role
			if ( ! $role->has_cap( $cap ) ) {
				// add a custom capability
				$role->add_cap( $cap, true );

			}

			//update the same cap for the current user who is installing or updating if logged in
			self::update_user_capability( $cap );
		}
	}//end method defaultRoleCapability

	/**
	 * Add any capability to the current user
	 *
	 * @param $capability_to_add
	 *
	 * @return void
	 */
	private static function update_user_capability( $capability_to_add ) {
		// Check if a user is logged in.
		if ( is_user_logged_in() ) {
			// Get the current user object.
			$user = wp_get_current_user();

			// Check if the user already has the capability.
			if ( ! $user->has_cap( $capability_to_add ) ) {
				// Add the capability.
				$user->add_cap( $capability_to_add );

				// Optional: Log the capability addition (for debugging or auditing).
				//error_log( 'Added capability "' . $capability_to_add . '" to user: ' . $user->user_login );

				// Optional: Force a refresh of the user's capabilities (sometimes needed).
				wp_cache_delete( $user->ID, 'users' );
				wp_cache_delete( 'user_meta', $user->ID );

			} else {
				// Optional: Log that the user already has the capability.
				//error_log( 'User: ' . $user->user_login . ' already has capability: ' . $capability_to_add );
			}
		} else {
			// Optional: Handle the case where no user is logged in.
			//error_log( 'No user is logged in.' );
		}
	}//end method update_user_capability	

	/**
	 * Create pages that the plugin relies on, storing page id's in variables.
	 */
	public static function create_pages() {

		$pages = apply_filters(
			'cbxmcratingreview_create_pages',
			[
				'cbxmcratingreview_singlereview'  => [
					'slug'    => _x( 'multireview-view', 'Page slug', 'cbxmcratingreview' ),
					'title'   => _x( 'Review', 'Page title', 'cbxmcratingreview' ),
					'content' => '[cbxmcratingreview_singlereview]',
				],
				'cbxmcratingreview_userdashboard' => [
					'slug'    => _x( 'multireview-dashboard', 'Page slug', 'cbxmcratingreview' ),
					'title'   => _x( 'Review Dashboard', 'Page title', 'cbxmcratingreview' ),
					'content' => '[cbxmcratingreview_userdashboard]',
				],

			]
		);

		foreach ( $pages as $key => $page ) {
			self::create_page( $key, esc_sql( $page['slug'] ), $page['title'], $page['content'] );
		}
	}//end method activate

	/**
	 * Create a page and store the ID in an option.
	 *
	 * @param string $key
	 * @param string $slug
	 * @param string $page_title
	 * @param string $page_content
	 *
	 * @return int|string|WP_Error|null
	 */
	public static function create_page( $key, $slug, $page_title = '', $page_content = '' ) {
		global $wpdb;

		$pages = get_option( 'cbxmcratingreview_pages' );
		if ( ! is_array( $pages ) ) {
			$pages = [];
		}

		$option_value = isset( $pages[ $key ] ) ? absint( $pages[ $key ] ) : 0;

		$page_id     = 0;
		$page_status = '';

		//if valid page id already exists
		if ( $option_value > 0 ) {
			$page_object = get_post( $option_value );

			if ( is_object( $page_object ) ) {
				//at least found a valid post
				$page_id     = $page_object->ID;
				$page_status = $page_object->post_status;

				if ( 'page' === $page_object->post_type && $page_object->post_status == 'publish' ) {
					return $page_id;
				}
			}
		}


		$page_id = intval( $page_id );
		if ( $page_id > 0 ) {
			//page found
			if ( $page_status == 'trash' ) {
				//if trashed then un trash it, it will be published automatically
				wp_untrash_post( $page_id );
			} else {
				$page_data = [
					'ID'          => $page_id,
					'post_status' => 'publish',
				];
				wp_update_post( $page_data );
			}
		} else {
			//search by slug for non trashed and then trashed, then if not found create one
			//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			if ( ( $page_id = intval( $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'page' AND post_status != 'trash' AND post_name = %s LIMIT 1;",
					$slug ) ) ) ) > 0 ) {
				//non trashed post found by slug
				//page found but not publish, so publish it
				//$page_id   = $page_found_by_slug;
				$page_data = [
					'ID'          => $page_id,
					'post_status' => 'publish',
				];

				wp_update_post( $page_data );
			} elseif ( ( $page_id = intval( $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug . '__trashed' ) ) ) ) > 0 ) {//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				//trash post found and un trash/publish it
				wp_untrash_post( $page_id );
			} else {
				$page_data = [
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'post_title'     => $page_title,
					'post_name'      => $slug,
					'post_content'   => $page_content,
					'comment_status' => 'closed',
				];
				$page_id   = wp_insert_post( $page_data );
			}
		}

		//let's update the option
		$pages[ $key ] = $page_id;
		update_option( 'cbxmcratingreview_pages', $pages );

		return $page_id;
	}//end method deactivate

	/**
	 * On plugin deactivate
	 */
	public static function deactivate() {
		//
	}//end url_utmy

	/**
	 * Returns codeboxr news feeds using transient cache
	 *
	 * @return false|mixed|\SimplePie\Item[]|null
	 */
	public static function codeboxr_news_feed() {
		$cache_key   = 'codeboxr_news_feed_cache';
		$cached_feed = get_transient( $cache_key );

		$news = false;

		if ( false === $cached_feed ) {
			include_once ABSPATH . WPINC . '/feed.php'; // Ensure feed functions are available
			$feed = fetch_feed( 'https://codeboxr.com/feed?post_type=post' );

			if ( is_wp_error( $feed ) ) {
				return false; // Return false if there's an error
			}

			$feed->init();

			$feed->set_output_encoding( 'UTF-8' );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        // this is the encoding parameter, and can be left unchanged in almost every case
			$feed->handle_content_type();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                // this double-checks the encoding type
			$feed->set_cache_duration( 21600 );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          // 21,600 seconds is six hours
			$limit  = $feed->get_item_quantity( 10 );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     // fetches the 18 most recent RSS feed stories
			$items  = $feed->get_items( 0, $limit );
			$blocks = array_slice( $items, 0, 10 );

			$news = [];
			foreach ( $blocks as $block ) {
				$url   = $block->get_permalink();
				$url   = CBXMCRatingReviewHelper::url_utmy( esc_url( $url ) );
				$title = $block->get_title();

				$news[] = [ 'url' => $url, 'title' => $title ];
			}

			set_transient( $cache_key, $news, HOUR_IN_SECONDS * 6 ); // Cache for 6 hours
		} else {
			$news = $cached_feed;
		}

		return $news;
	}// end function dashboard_menus

	/**
	 * Add utm params to any url
	 *
	 * @param $url
	 *
	 * @return mixed|string
	 * @since 2.0.0
	 */
	public static function url_utmy( $url = '' ) {
		if ( $url == '' ) {
			return $url;
		}

		$url = add_query_arg( array(
			'utm_source'   => 'plgdashboardinfo',
			'utm_medium'   => 'plgdashboard',
			'utm_campaign' => 'wpfreemium',
		), $url );

		return $url;
	}//end method codeboxr_news_feed

	/**
	 * Login form
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function guest_login_forms() {
		$forms = [];

		$forms['wordpress'] = esc_html__( 'WordPress Core Login Form', 'cbxmcratingreview' );
		$forms['none']      = esc_html__( 'Don\'t show login form, show default login url', 'cbxmcratingreview' );
		$forms['off']       = esc_html__( 'Show nothing!', 'cbxmcratingreview' );

		return apply_filters( 'cbxmcratingreview_guest_login_forms', $forms );
	}//end guest_login_forms
}//end class CBXMCRatingReviewHelper