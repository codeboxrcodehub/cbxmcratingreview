<?php

namespace CBX\MCRatingReview\Controllers;

use CBX\MCRatingReview\Models\RatingReviewLogAvg;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;

use Exception;
use WP_REST_Request;
use WP_REST_Response;
use Illuminate\Database\QueryException;
use Rakit\Validation\Validator;

use CBX\MCRatingReview\CBXMCRatingReviewSettings;

/**
 * Class Avg Log Controller
 *
 * @since 2.0.0
 */
class AvgLogController {

	/**
	 * Get rating log List
	 *
	 * @return WP_REST_Response
	 * @since 2.0.0
	 */
	public function getAvgLogs( WP_REST_Request $request ) {
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
			$filter['form']     = isset( $data['form'] ) ? absint( wp_unslash( $data['form'] ) ) : null;

			$sort = strtolower( $filter['sort'] );

			$logs = RatingReviewLogAvg::query()->with( 'form', 'post' );

			if ( $filter['search'] ) {
				$filter['search'] = $wpdb->esc_like( $filter['search'] );
				$logs             = $logs->where( 'comment', 'LIKE', '%' . $filter['search'] . '%' );
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
				'info'    => esc_html__( 'List of log average', 'cbxmcratingreview' )
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
	} //end method getAvgLogs

	/**
	 * Log delete
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 * @since 2.0.0
	 */
	public function deleteAvgLog( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$success_count = $fail_count = 0;

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized', 'cbxmcratingreview' ) );
			}

			$data = $request->get_params();
			if ( empty( $data['id'] ) ) {
				throw new Exception( esc_html__( 'Log id is required.', 'cbxmcratingreview' ) );
			}


			if ( is_array( $data['id'] ) && count( $data['id'] ) ) {
				foreach ( $data['id'] as $id ) {
					$log = RatingReviewLogAvg::query()->find( absint( $id ) );
					if ( $log ) {
						if ( $log->delete() ) {
							$success_count ++;
						} else {
							$fail_count ++;
						}
					}
				}
			} else {
				$log = RatingReviewLogAvg::query()->find( intval( $data['id'] ) );
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
	} //end method deleteAvgLog
}//end class AvgLogController