<?php

namespace CBX\MCRatingReview\Controllers;

use CBX\MCRatingReview\Models\RatingReviewForm;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

use Exception;
use WP_REST_Request;
use WP_REST_Response;
use Illuminate\Database\QueryException;
use Rakit\Validation\Validator;

/**
 * Class Form Controller
 *
 * @since 2.0.0
 */
class FormController {

	/**
	 * Get Email form List
	 *
	 * @return WP_REST_Response
	 * @since 2.0.0
	 */
	public function getForms( WP_REST_Request $request ) {
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
			$filter['source']   = isset( $data['source'] ) ? sanitize_text_field( wp_unslash( $data['source'] ) ) : null;


			$sort = strtolower( $filter['sort'] );

			if ( isset( $data['date'] ) && $data['date'] !== '' ) {
				if ( str_contains( $data['date'], ' to ' ) ) {
					$dates = explode( ' to ', $data['date'] );
				} else {
					$dates = $data['date'];
				}
				$filter['date'] = $dates;
			}


			$forms = RatingReviewForm::query();

			if ( $filter['search'] ) {
				$filter['search'] = $wpdb->esc_like( $filter['search'] );
				$forms            = $forms->where( 'subject', 'LIKE', '%' . $filter['search'] . '%' );
			}

			if ( isset( $filter['status'] ) && $filter['status'] != null ) {
				$forms = $forms->where( 'status', absint( $filter['status'] ) );
			}

			$forms = $forms->orderBy( $filter['order_by'], $filter['sort'] )->paginate( $filter['limit'], '*', 'page',
				$filter['page'] )->toArray();

			$response->set_data( [
				'success' => true,
				'data'    => $forms,
				'info'    => esc_html__( 'List of forms', 'cbxmcratingreview' )
			] );

		} catch ( QueryException $e ) {
			// Check if the error is due to a missing table
			if ( str_contains( $e->getMessage(), 'Base table or view not found' ) ) {
				$response->set_data( [
					'info'    => esc_html__( 'Form table does not exist. Please check the database structure.', 'cbxmcratingreview' ),
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
	} //end getForms get_form_list


	/**
	 * Get form fields data
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 * @throws Exception
	 * @since 2.0.0
	 */
	public function getForm( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		//01. Check if current user is logged in
		if ( ! is_user_logged_in() ) {
			throw new \Exception( esc_html__( 'Unauthorized request', 'cbxmcratingreview' ) );
		}


		if ( isset( $request['id'] ) ) {
			$form = RatingReviewForm::where( 'id', absint( $request['id'] ) )->first();

			if ( $form ) {
				$response->set_data( $form );
			} else {
				$response->set_data( [ 'error' => esc_html__( 'Form Not Found', 'cbxmcratingreview' ) ] );
			}
		}

		return $response;
	}//end method getForm

	/**
	 * Form delete
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function deleteForm( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$success_count = $fail_count = 0;

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized', 'cbxmcratingreview' ) );
			}

			$data = $request->get_params();
			if ( empty( $data['id'] ) ) {
				throw new Exception( esc_html__( 'Form id is required.', 'cbxmcratingreview' ) );
			}


			if ( is_array( $data['id'] ) && count( $data['id'] ) ) {
				foreach ( $data['id'] as $id ) {
					$form = RatingReviewForm::query()->find( absint( $id ) );
					if ( $form ) {
						if ( $form->delete() ) {
							$success_count ++;
						} else {
							$fail_count ++;
						}
					}
				}
			} else {
				$form = RatingReviewForm::query()->find( intval( $data['id'] ) );
				if ( $form ) {
					if ( $form->delete() ) {
						$success_count ++;
					} else {
						$fail_count ++;
					}

				}
			}

			$success_msg = $fail_msg = '';
			if ( $success_count > 0 ) {
				/* translators: %d: form successfully deleted count */
				$success_msg = sprintf( esc_html__( '%d form(s) deleted successfully. ', 'cbxmcratingreview' ), $success_count );

			}

			if ( $fail_count > 0 ) {
				/* translators: %d: form delete fail count */
				$fail_msg = sprintf( esc_html__( '%d form(s) can`t be deleted as they may have dependency.', 'cbxmcratingreview' ), $fail_count );

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
	} //end method deleteForm

	/**
	 * Save rating form
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 * @since 2.0.0
	 */
	public function saveForm( WP_REST_Request $request ) {
		try {
			$errorHappened = false;
			$errorMessages = [];

			$ratingFormData = $request->get_params();

			$validator        = new Validator;
			$validation_rules = [];


			$response = new WP_REST_Response();
			$response->set_status( 200 );

			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized request', 'cbxmcratingreview' ) );
			}

			$form_fields = CBXMCRatingReviewHelper::form_default_fields();
			$star_titles = CBXMCRatingReviewHelper::star_default_titles();
			$form_count  = CBXMCRatingReviewHelper::getRatingForms_Count();

			$formSavableData = [];


			//special care for id
			$formSavableData['id'] = $form_id = isset( $ratingFormData['id'] ) ? absint( $ratingFormData['id'] ) : 0;
			unset( $form_fields['id'] );


			if ( $form_id == 0 && $form_count > 0 ) {
				$can_add_form = apply_filters( 'cbxmcratingreview_add_more_forms', false );
			} else {
				$can_add_form = true;
			}

			//if not unlimited form
			if ( $can_add_form === false ) {
				throw new Exception( esc_html__( 'Sorry, in free version only one form can be created', 'cbxmcratingreview' ) );
			}

			//general fields starts
			foreach ( $form_fields as $key => $field ) {
				$default_value = isset( $field['default'] ) ? $field['default'] : null;

				$extra_field = ( isset( $field['extrafield'] ) && $field['extrafield'] ) ? true : false;

				$required_field = ( isset( $field['required'] ) && $field['required'] ) ? $field['required'] : false;
				$multiple_field = ( isset( $field['multiple'] ) && $field['multiple'] ) ? $field['multiple'] : false;

				if ( $extra_field ) {
					$value = isset( $ratingFormData['extrafields'][ $key ] ) ? $ratingFormData['extrafields'][ $key ] : $default_value;

					if ( ! isset( $ratingFormData[ $key ] ) && $required_field ) {
						$validation_rules[ 'extrafields.' . $key ] = 'required';
					}

					if ( $field['type'] == 'select' ) {
						if ( $multiple_field && $required_field ) {
							if ( empty( $value ) || ! is_array( $value ) ) {
								$validation_rules[ 'extrafields.' . $key ] = 'required';
							}
						}
					}

				} else {
					$value = isset( $ratingFormData[ $key ] ) ? $ratingFormData[ $key ] : $default_value;

					if ( ! isset( $ratingFormData[ $key ] ) && $required_field ) {
						$validation_rules[ $key ] = 'required';
					}

					if ( $field['type'] == 'select' ) {
						if ( $multiple_field && $required_field ) {
							if ( empty( $value ) || ! is_array( $value ) ) {
								$validation_rules[ $key ] = 'required';
							}
						}
					}
				}


				//field is ok, let move save for db entry
				if ( $extra_field ) {
					$formSavableData['extrafields'][ $key ] = $value;
				} else {
					$formSavableData[ $key ] = $value;
				}
			}//end foreach for generic fields
			//general fields ends.


			//validating custom criteria
			if ( isset( $ratingFormData['custom_criteria'] ) && ! empty( $ratingFormData['custom_criteria'] ) ) {

				$criteria_index  = 0;
				$max_criteria_id = 0;
				//process every single criteria
				foreach ( $ratingFormData['custom_criteria'] as $criteria ) {

					/* translators: %d: Criteria id */
					$criteria_label = ( isset( $criteria['label'] ) && $criteria['label'] != '' ) ? sanitize_text_field( $criteria['label'] ) : sprintf( __( 'Criteria %d', 'cbxmcratingreview' ), ( $criteria_index + 1 ) );
					$criteria_id    = isset( $criteria['criteria_id'] ) ? absint( $criteria['criteria_id'] ) : $criteria_index;

					$max_criteria_id = $criteria_id > $max_criteria_id ? $criteria_id : $max_criteria_id;


					$formSavableData['custom_criteria'][ $criteria_index ]['label']       = $criteria_label;
					$formSavableData['custom_criteria'][ $criteria_index ]['criteria_id'] = $criteria_id;

					$star_index = 0;
					if ( isset( $criteria['stars'] ) && sizeof( $criteria['stars'] ) > 0 ) {
						//process every single star
						foreach ( $criteria['stars'] as $stars ) {

							$star_title = ( isset( $stars['title'] ) && ( $stars['title'] != '' ) ) ? sanitize_text_field( $stars['title'] ) : $star_titles[ $star_index % 5 ];

							$formSavableData['custom_criteria'][ $criteria_index ]['stars'][ $star_index ]['title'] = $star_title;

							$star_index ++;
						}//end star process


					} else {
						$errorHappened = true;
						/* translators: %s: Criteria label */
						$errorMessages['custom_criteria'][ $criteria_index ] = sprintf( esc_html__( 'Criteria "%s" must have at least one star', 'cbxmcratingreview' ), $criteria_label );
					}


					$criteria_index ++;

				}//end criteria process


				$formSavableData['custom_criteria'] = maybe_serialize( $formSavableData['custom_criteria'] );

			} else {
				$errorHappened                               = true;
				$errorMessages['custom_criteria']['general'] = esc_html__( 'The form must have at least one criteria', 'cbxmcratingreview' );
			}
			//end validating custom criteria
			//end handle criteria

			//handle question
			$formSavableData['custom_question'] = [];

			//validating custom questions
			if ( isset( $ratingFormData['custom_question'] ) && ! empty( $ratingFormData['custom_question'] ) ) {

				$emptyTitle = 0;
				foreach ( $ratingFormData['custom_question'] as $index => $question ) {

					if ( empty( $question['type'] ) ) {
						continue;
					}
					if ( ! empty( $question['title'] ) ) {

						$formSavableData['custom_question'][ $index ] = [];

						$formSavableData['custom_question'][ $index ]['title'] = sanitize_text_field( wp_unslash( $question['title'] ) );

						$formSavableData['custom_question'][ $index ]['required'] = isset( $question['required'] ) ? absint( $question['required'] ) : 0;
						$formSavableData['custom_question'][ $index ]['enabled']  = isset( $question['enabled'] ) ? absint( $question['enabled'] ) : 0;

						if ( isset( $question['multiple'] ) ) {
							$formSavableData['custom_question'][ $index ]['multiple'] = absint( $question['multiple'] );
						}
						if ( isset( $question['last_count'] ) ) {
							$formSavableData['custom_question'][ $index ]['last_count'] = absint( $question['last_count'] );
						}
						if ( isset( $question['placeholder'] ) ) {
							$formSavableData['custom_question'][ $index ]['placeholder'] = sanitize_text_field( wp_unslash( $question['placeholder'] ) );
						}

						$type                                                 = sanitize_text_field( wp_unslash( $question['type'] ) );
						$formSavableData['custom_question'][ $index ]['type'] = $type;

						if ( $type == 'number' ) {
							$formSavableData['custom_question'][ $index ]['min']  = isset( $question['min'] ) ? absint( $question['min'] ) : 0;
							$formSavableData['custom_question'][ $index ]['max']  = isset( $question['min'] ) ? absint( $question['min'] ) : 100;
							$formSavableData['custom_question'][ $index ]['step'] = isset( $question['step'] ) ? absint( $question['step'] ) : 1;
						}


						if ( isset( $question['options'] ) ) {
							$options = $question['options'];
							foreach ( $options as $option_index => $option ) {
								$option_title                                                                     = sanitize_text_field( wp_unslash( $option['text'] ) );
								$formSavableData['custom_question'][ $index ]['options'][ $option_index ]['text'] = ( $option_title != '' ) ? $option_title : esc_html__( 'Untitled Option', 'cbxmcratingreview' );

								if ( $option_title == '' ) {
									$emptyTitle ++;
								}
							}
						}

					} else {
						$emptyTitle ++;
					}
				}//end validating custom questions

				$formSavableData['custom_question'] = maybe_serialize( $formSavableData['custom_question'] );

				if ( ( $emptyTitle > 0 ) ) {
					$errorHappened   = true;
					$errorText       = esc_html__( 'One of your question title or option title field is empty', 'cbxmcratingreview' );
					$errorMessages[] = $errorText;
				}
			} else {
				$formSavableData['custom_question'] = maybe_serialize( [] ); //anything better
			}
			//end handle question


			$validation = $validator->validate( $formSavableData, $validation_rules );

			if ( $validation->fails() ) {
				$errors = $validation->errors();
				$response->set_data( [
					'success' => false,
					'errors'  => $errors->firstOfAll(),
					'info'    => esc_html__( 'Server error', 'cbxmcratingreview' ),
				] );

				return $response;
			}

			//if not error happened then update/insert
			if ( ! $errorHappened && empty( $errorMessages ) ) {

				$formSavableData['extrafields'] = maybe_serialize( $formSavableData['extrafields'] );

				if ( isset( $ratingFormData['id'] ) ) {
					$save = RatingReviewForm::query()->where( 'id', $request['id'] )->update( $formSavableData );
					$save = RatingReviewForm::find( $request['id'] );

					if ( $save ) {
						$response->set_data( [
							'success' => true,
							'data'    => $save,
							'info'    => esc_html__( 'Form Updated', 'cbxmcratingreview' )
						] );
					}
				} else {
					$save = RatingReviewForm::query()->create( $formSavableData );

					if ( $save ) {
						$response->set_data( [
							'success' => true,
							'data'    => $save,
							'info'    => esc_html__( 'Form Created', 'cbxmcratingreview' )
						] );
					}
				}
			}//end if not error happened then update/insert

			//if validation failed or error happened
			if ( $errorHappened && ! empty( $errorMessages ) ) {

				$response->set_data( [
					'info'    => $errorMessages,
					'success' => false
				] );
			}
		} catch ( Exception $e ) {
			$response->set_data( [
				'info'    => $e->getMessage(),
				'success' => false
			] );
		}

		return $response;
	}//end method saveForm
}//end class FormController