<?php
namespace CBX\MCRatingReview\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Models\RatingReviewLog;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

use Exception;
use WP_REST_Request;
use WP_REST_Response;
use Illuminate\Database\QueryException;
use Rakit\Validation\Validator;

use CBX\MCRatingReview\CBXMCRatingReviewSettings;

/**
 * Class Log Controller
 *
 * @since 2.0.0
 */
class LogController {

	/**
	 * Get rating log List
	 *
	 * @return WP_REST_Response
	 * @since 2.0.0
	 */
	public function getLogs( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		try {
			//01. Check if current user is logged in
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized request', 'cbxmcratingreview' ) );
			}

			global $wpdb;

			$data = $request->get_params();

			$filter             = [];
			$filter['limit']    = isset( $data['limit'] ) ? absint( $data['limit'] ) : 10;
			$filter['page']     = isset( $data['page'] ) ? absint( $data['page'] ) : 1;
			$filter['order_by'] = isset( $data['order_by'] ) ? sanitize_text_field( wp_unslash( $data['order_by'] ) ) : 'id';
			$filter['sort']     = $sort = isset( $data['sort'] ) ? sanitize_text_field( wp_unslash( $data['sort'] ) ) : 'desc';
			$filter['search']   = isset( $data['search'] ) ? sanitize_text_field( wp_unslash( $data['search'] ) ) : null;
			$filter['status']   = isset( $data['status'] ) ? sanitize_text_field( wp_unslash( $data['status'] ) ) : null;
			$filter['post_id']  = isset( $data['post_id'] ) ? absint( wp_unslash( $data['post_id'] ) ) : null;
			$filter['form']     = isset( $data['form'] ) ? absint( wp_unslash( $data['form'] ) ) : null;

			$sort = strtolower( $filter['sort'] );

			if ( isset( $data['date'] ) && $data['date'] !== '' ) {
				if ( str_contains( $data['date'], ' to ' ) ) {
					$dates = explode( ' to ', $data['date'] );
				} else {
					$dates = $data['date'];
				}
				$filter['date'] = $dates;
			}


			$logs = RatingReviewLog::query()->with( 'user', 'post' );

			if ( $filter['search'] ) {
				$filter['search'] = $wpdb->esc_like( $filter['search'] );
				$logs             = $logs->where( 'comment', 'LIKE', '%' . $filter['search'] . '%' );
			}

			if ( isset( $filter['date'] ) && $filter['date'] && is_array( $filter['date'] ) ) {
				$logs = $logs->whereBetween( 'date_created', $filter['date'] );
			}

			if ( isset( $filter['status'] ) && $filter['status'] != null ) {
				$logs = $logs->where( 'status', absint( $filter['status'] ) );
			}

			if ( isset( $filter['post_id'] ) && $filter['post_id'] != null ) {
				$logs = $logs->where( 'post_id', absint( $filter['post_id'] ) );
			}

			if ( isset( $filter['form'] ) && $filter['form'] != null ) {
				$logs = $logs->where( 'form_id', absint( $filter['form'] ) );
			}

			$logs = $logs->orderBy( $filter['order_by'], $filter['sort'] )->paginate( $filter['limit'], '*', 'page',
				$filter['page'] )->toArray();

			// $response->set_data($logs);

			$response->set_data( [
				'success' => true,
				'data'    => $logs,
				'info'    => esc_html__( 'List of logs', 'cbxmcratingreview' )
			] );

		} catch ( QueryException $e ) {
			// Check if the error is due to a missing table
			if ( str_contains( $e->getMessage(), 'Base table or view not found' ) ) {
				$response->set_data( [
					'info'    => esc_html__( 'Log table does not exist. Please check the database structure.', 'cbxmcratingreview' ),
					'success' => false
				] );
			} else {
				$response->set_data( [
					'info'    => esc_html__( 'Something Went Wrong. Please try again later.', 'cbxmcratingreview' ),
					'success' => false
				] );
			}
		} catch ( Exception $e ) {
			$response->set_data( [
				'info'    => esc_html__( 'Something Went Wrong. Please try again later.', 'cbxmcratingreview' ),
				'success' => false
			] );
		}

		return $response;
	} //end method getLogs


	/**
	 * Get log fields data
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 * @throws Exception
	 * @since 2.0.0
	 */
	public function getLog( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		//01. Check if current user is logged in
		if ( ! is_user_logged_in() ) {
			throw new \Exception( esc_html__( 'Unauthorized request', 'cbxmcratingreview' ) );
		}


		if ( isset( $request['id'] ) ) {
			$log = RatingReviewLog::where( 'id', absint( $request['id'] ) )->with( 'user', 'post', 'modUser', 'form' )->first();


			if ( $log ) {
				$log = $log->toArray();

				if ( isset( $log['questions'] ) ) {
					foreach ( $log['questions'] as $key => $value ) {
						if ( is_serialized( $value ) ) {
							$log['questions'][ $key ] = maybe_unserialize( $value );
						}
					}
				}

				$response->set_data( $log );
			} else {
				$response->set_data( [ 'error' => esc_html__( 'Log Not Found', 'cbxmcratingreview' ) ] );
			}
		}

		return $response;
	}//end method getLog

	/**
	 * Log delete
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function deleteLog( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$success_count = $fail_count = 0;

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized', 'cbxmcratingreview' ) );
			}

			// //02. Check for user core capability
			// if ( ! current_user_can( 'cbxmcratingreview_log_delete' ) ) {
			// 	throw new \Exception( esc_html__( 'Sorry, you don\'t have enough permission to do this action', 'cbxmcratingreview' ) );
			// }

			$data = $request->get_params();
			if ( empty( $data['id'] ) ) {
				throw new Exception( esc_html__( 'Log id is required.', 'cbxmcratingreview' ) );
			}


			if ( is_array( $data['id'] ) && count( $data['id'] ) ) {
				foreach ( $data['id'] as $id ) {
					$log = RatingReviewLog::query()->find( absint( $id ) );
					if ( $log ) {
						if ( $log->delete() ) {
							$success_count ++;
						} else {
							$fail_count ++;
						}
					}
				}
			} else {
				$log = RatingReviewLog::query()->find( intval( $data['id'] ) );
				if ( $log ) {
					if ( $log->delete() ) {
						$success_count ++;
					} else {
						$fail_count ++;
					}

				}
			}

			$success_msg = $fail_msg = '';
			if ( $success_count > 0 ) {
				/* translators: %d: log successfully deleted count */
				$success_msg = sprintf( esc_html__( '%d log(s) deleted successfully. ', 'cbxmcratingreview' ), $success_count );

			}

			if ( $fail_count > 0 ) {
				/* translators: %d: log delete fail count */
				$fail_msg = sprintf( esc_html__( '%d log(s) can`t be deleted as they may have dependency.', 'cbxmcratingreview' ), $fail_count );

			}

			$response->set_data( [
				'success' => true,
				'info'    => $success_msg . $fail_msg
			] );

			return $response;

		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'err'     => $e->getMessage(),
				/* translators: 1: Success count 2. Fail count  */
				'info'    => sprintf( esc_html__( 'Incomplete deletion. %1$d successfully and %2$d failed', 'cbxmcratingreview' ), $success_count, $fail_count ),
			] );

			return $response;
		}
	} //end method deleteLog

	/**
	 * Save Log
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 * @since 2.0.0
	 */
	public function saveLog( WP_REST_Request $request ) {

		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$settings = new CBXMCRatingReviewSettings();

		$show_headline    = intval( $settings->get_field( 'show_headline', 'cbxmcratingreview_common_config', 1 ) );
		$show_comment     = intval( $settings->get_field( 'show_comment', 'cbxmcratingreview_common_config', 1 ) );
		$require_headline = intval( $settings->get_field( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
		$require_comment  = intval( $settings->get_field( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );

		$default_status = intval( $settings->get_field( 'default_status', 'cbxmcratingreview_common_config', 1 ) );


		$submit_data = $request->get_params();

		$validation_errors = $response_data_arr = [];
		$ok_to_process     = false;
		$success_msg_info  = '';


		if ( is_user_logged_in() ) {

			$form_id   = isset( $submit_data['form_id'] ) ? intval( $submit_data['form_id'] ) : 0;
			$post_id   = isset( $submit_data['post_id'] ) ? intval( $submit_data['post_id'] ) : 0;
			$review_id = isset( $submit_data['id'] ) ? intval( $submit_data['id'] ) : 0;

			//get the form setting
			$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );

			$enable_question  = isset( $form['enable_question'] ) ? intval( $form['enable_question'] ) : 0;
			$custom_criterias = isset( $form['custom_criteria'] ) ? $form['custom_criteria'] : [];
			$custom_questions = isset( $form['custom_question'] ) ? $form['custom_question'] : [];

			$rating_scores      = isset( $submit_data['ratings']['ratings_stars'] ) ? $submit_data['ratings']['ratings_stars'] : [];
			$rating_score_total = 0;
			$rating_score_count = 0;

			$review_headline = isset( $submit_data['headline'] ) ? sanitize_text_field( $submit_data['headline'] ) : '';
			$review_comment  = isset( $submit_data['comment'] ) ? wp_kses( $submit_data['comment'],
				CBXMCRatingReviewHelper::allowedHtmlTags() ) : '';

			$questions_store = [];
			$ratings_stars   = [];


			$default_status = apply_filters( 'cbxmcratingreview_review_review_default_status', $default_status,
				$post_id );
			$new_status     = isset( $submit_data['status'] ) ? intval( $submit_data['status'] ) : $default_status;


			if ( $review_id <= 0 ) {
				$validation_errors['top_errors']['log_id']['log_id_wrong'] = esc_html__( 'Sorry! Invalid review id. Please check and try again.',
					'cbxmcratingreview' );
			} else {
				$review_info_old = cbxmcratingreview_singleReview( $review_id );
				if ( $review_info_old == null ) {
					$validation_errors['top_errors']['log']['log_wrong'] = esc_html__( 'Sorry! Invalid review. Please check and try again.',
						'cbxmcratingreview' );
				}
			}

			if ( $post_id <= 0 ) {
				$validation_errors['top_errors']['post']['post_id_wrong'] = esc_html__( 'Sorry! Invalid post. Please check and try again.',
					'cbxmcratingreview' );
			}

			//rating validation
			if ( is_array( $rating_scores ) && sizeof( $rating_scores ) > 0 ) {
				$rating_score_count = sizeof( $rating_scores );

				foreach ( $custom_criterias as $criteria_index => $custom_criteria ) {
					$criteria_id = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $criteria_index );

					/* translators: %d: Criteria id */
					$label = isset( $custom_criteria['label'] ) ? esc_attr( $custom_criteria['label'] ) : sprintf( esc_html__( 'Untitled criteria - %d', 'cbxmcratingreview' ),
						$criteria_id );

					$stars_length = isset( $rating_scores[ $criteria_id ]['stars_length'] ) ? intval( $rating_scores[ $criteria_id ]['stars_length'] ) : 0;


					if ( isset( $rating_scores[ $criteria_id ] ) ) {
						$rating_score     = isset( $rating_scores[ $criteria_id ]['score'] ) ? $rating_scores[ $criteria_id ]['score'] : 0;
						$score_percentage = ( $stars_length != 0 ) ? ( $rating_score * 100 ) / $stars_length : 0; //scale in 100
						$score_standard   = ( $score_percentage != 0 ) ? ( ( $score_percentage * 5 ) / 100 ) : 0; //scale in 5
						$score_round      = ceil( $rating_score );
						$round_percentage = ( $stars_length != 0 ) ? ( $score_round * 100 ) / $stars_length : 0;

						$ratings_stars[ $criteria_id ] = [
							'stars_length'     => $stars_length,
							'score'            => $rating_score,
							'score_percentage' => $score_percentage,
							'score_standard'   => number_format( $score_standard, 2 ), //score in 5
							'score_round'      => $score_round,
							'round_percentage' => $round_percentage
						];

						$rating_score_total += floatval( $score_percentage );

						if ( $rating_score <= 0 || $rating_score > $stars_length ) {
							/* translators: %s: Criteria label  */
							$validation_errors['cbxmcratingreview_rating_score'][ 'rating_score_wrong_' . $criteria_id ] = sprintf( __( 'Sorry! Invalid rating score for criteria <strong>%s</strong>. Please check and try again.',
								'cbxmcratingreview' ), $label );
						}
					} elseif ( ! isset( $rating_scores[ $criteria_id ] ) ) {
						/* translators: %s: Criteria label  */
						$validation_errors['cbxmcratingreview_rating_score'][ 'rating_score_wrong_' . $criteria_id ] = sprintf( __( 'Sorry! Invalid rating score for criteria <strong>%s</strong>. Please check and try again.',
							'cbxmcratingreview' ), $label );
					}
				}//end for each criteria
			} else {
				//error checking if review only submit approved
				$validation_errors['cbxmcratingreview_rating_score']['rating_score_wrong'] = esc_html__( 'Sorry! Invalid rating score or no rating selected. Please check and try again.',
					'cbxmcratingreview' );
			}//end rating validation


			//questions validations
			$questions = isset( $submit_data['questions'] ) ? $submit_data['questions'] : [];

			//if question enabled for this form and question submitted
			if ( $enable_question && is_array( $questions ) && sizeof( $questions ) ) {
				//for each form questions
				foreach ( $custom_questions as $question_index => $question ) {
					$field_type = isset( $question['type'] ) ? $question['type'] : '';
					$enabled    = isset( $question['enabled'] ) ? intval( $question['enabled'] ) : 0;

					/* translators: %d: Question index  */
					$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d',
						'cbxmcratingreview' ), intval( $question_index ) );

					if ( $field_type != '' && $enabled ) {
						$required = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
						$multiple = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;
						//if question answered
						if ( isset( $questions[ $question_index ] ) ) {
							$answer = $questions[ $question_index ];

							if ( $field_type == 'text' || $field_type == 'textarea' || $field_type == 'number' || ( $field_type == 'select' && $multiple == 0 ) ) {
								if ( $required && $answer == '' ) {
									/* translators: %s: Form Field Title label  */
									$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is blank but required. Please check and try again.',
										'cbxmcratingreview' ), $title );
								}
							} elseif ( $field_type == 'select' && $multiple ) {

								if ( $required && sizeof( array_filter( $answer, [
										'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
										'arrayFilterRemoveEmpty'
									] ) ) == 0 ) {
									/* translators: %s: Form Field Title label  */
									$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.',
										'cbxmcratingreview' ), $title );
								}
							} elseif ( $field_type == 'checkbox' ) {

							} elseif ( $field_type == 'multicheckbox' ) {
								if ( $required && sizeof( array_filter( $answer, [
										'\CBX\MCRatingReview\Helpers\CBXMCRatingReviewQuestionHelper',
										'arrayFilterRemoveEmpty'
									] ) ) == 0 ) {
									/* translators: %s: Form Field Title label  */
									$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.',
										'cbxmcratingreview' ), $title );
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
							/* translators: %s: Form Field Title label  */
							$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.',
								'cbxmcratingreview' ), $title );
						}
					}
				}
			}//end if question answer submitted
			//end question validation


			if ( $show_headline && $require_headline && $review_headline == '' ) {
				$validation_errors['cbxmcratingreview_review_headline']['review_headline_empty'] = esc_html__( 'Please provide title',
					'cbxmcratingreview' );
			}
			if ( $show_comment && $require_comment && $review_comment == '' ) {
				$validation_errors['cbxmcratingreview_review_comment']['review_comment_empty'] = esc_html__( 'Please provide review',
					'cbxmcratingreview' );
			}


		} else {
			$validation_errors['top_errors']['user']['user_guest'] = esc_html__( 'You aren\'t currently logged in. Please login to rate.',
				'cbxmcratingreview' );
		}

		$validation_errors = apply_filters( 'cbxmcratingreview_review_adminedit_validation_errors', $validation_errors,
			$form_id, $post_id, $submit_data );

		if ( sizeof( $validation_errors ) > 0 ) {

		} else {
			$old_status = $review_info_old['status'];

			$ok_to_process     = true;
			$log_update_status = false;

			$attachment = maybe_unserialize( $review_info_old['attachment'] );

			$attachment = apply_filters( 'cbxmcratingreview_review_adminedit_attachment', $attachment, $form_id,
				$post_id, $submit_data, $review_id );

			$extra_params = maybe_unserialize( $review_info_old['extraparams'] );
			$extra_params = apply_filters( 'cbxmcratingreview_review_adminedit_extraparams', $extra_params, $form_id,
				$post_id, $submit_data, $review_id );

			$user_id = intval( get_current_user_id() );

			$rating_avg_percentage = $rating_score_total / $rating_score_count; //in 100%

			$rating_avg_score = ( $rating_avg_percentage != 0 ) ? ( $rating_avg_percentage * 5 ) / 100 : 0; //scale within 5

			$ratings = [
				'ratings_stars'  => $ratings_stars,
				'avg_percentage' => $rating_avg_percentage,
				'avg_score'      => $rating_avg_score
			];

			// insert rating log
			$data = [
				'score'         => number_format( $rating_avg_score, 2 ),
				'headline'      => $review_headline,
				'comment'       => $review_comment,
				'extraparams'   => maybe_serialize( $extra_params ),
				'attachment'    => maybe_serialize( $attachment ),
				'status'        => $new_status,
				'mod_by'        => $user_id,
				'date_modified' => current_time( 'mysql' ),
				'ratings'       => maybe_serialize( $ratings ),
				'questions'     => maybe_serialize( $questions_store )
			];

			$data = apply_filters( 'cbxmcratingreview_review_adminedit_data', $data, $form_id, $post_id, $submit_data, $review_id );

			$log_update_status = RatingReviewLog::where( 'id', $review_id )->update( $data );

			if ( $log_update_status != false ) {

				do_action( 'cbxmcratingreview_review_adminedit_just_success', $form_id, $post_id, $submit_data, $review_id );

				$success_msg_info = esc_html__( 'Review updated successfully', 'cbxmcratingreview' );

				$success_msg_info = apply_filters( 'cbxmcratingreview_review_adminedit_success_info', $success_msg_info,
					'success' );

				$review_info = cbxmcratingreview_singleReview( $review_id );

				//if status change
				if ( $old_status != $new_status ) {
					if ( $new_status == 1 ) {
						do_action( 'cbxmcratingreview_review_publish', $review_info, $review_info_old );
					} else {
						do_action( 'cbxmcratingreview_review_unpublish', $review_info, $review_info_old );

					}
					do_action( 'cbxmcratingreview_review_status_change', $old_status, $new_status, $review_info,
						$review_info_old );

				}//end status change detected
				else {
					//simple update without status change
					do_action( 'cbxmcratingreview_review_update_without_status', $new_status, $review_info,
						$review_info_old );
				}

				$response_data_arr = apply_filters( 'cbxmcratingreview_review_adminedit_response_data',
					$response_data_arr, $post_id, $submit_data, $review_info );


				do_action( 'cbxmcratingreview_review_adminedit_success', $form_id, $post_id, $submit_data, $review_info,
					$review_info_old );

			}
		}//end review submit validation

		$response->set_data( [
			'success' => $ok_to_process,
			'info'    => $success_msg_info
		] );


		return $response;

	}//end saveLog save
}//end class LogController