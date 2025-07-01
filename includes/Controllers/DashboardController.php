<?php
namespace CBX\MCRatingReview\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Exception;
use CBX\MCRatingReview\MigrationManage;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use CBX\MCRatingReview\Helpers\CBXMCRatingReviewAdminHelper;

/**
 * Dashboard controller for miscellaneous tasks
 *
 * @since 2.0.0
 */
class DashboardController {

	/**
	 * Get get global overview data
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 * @since 2.0.0
	 */
	public function getGlobalOverviewData( \WP_REST_Request $request ) {
		$response = new \WP_REST_Response();
		$response->set_status( 200 );

		try {
			$data = $request->get_params();

			// Get the current month and year, or fetch them from request params if needed
			$month = isset( $data['month'] ) ? intval( $data['month'] ) : gmdate( 'm' );
			$year  = isset( $data['year'] ) ? intval( $data['year'] ) : gmdate( 'Y' );

			$daily_review     = CBXMCRatingReviewHelper::getDailyReviewCounts( $year, $month );
			$labels           = array_map( 'strval', array_keys( $daily_review ) );
			$backgroundColors = array_map( fn() => sprintf( '#%06X', wp_rand( 0, 0xFFFFFF ) ), range( 1, 12 ) );

			// Prepare the chart data format
			$dailyReviewData = [
				'labels'   => $labels,
				'datasets' => [
					[
						'label'           => 'Review Count',
						'backgroundColor' => $backgroundColors,
						'data'            => $daily_review
					]
				]
			];


			$response->set_data( [
				'success'         => true,
				'dailyReviewData' => $dailyReviewData,
			] );

			return $response;
		} catch ( \Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );

			return $response;
		}
	}//end method getGlobalOverviewData

	/**
	 * Get latest listing data
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 * @since 2.0.0
	 */
	public function getLatestListingData( \WP_REST_Request $request ) {
		$response = new \WP_REST_Response();
		$response->set_status( 200 );

		try {

			$reviews = CBXMCRatingReviewAdminHelper::dashboardReviews();

			$response_data            = [];
			$response_data['reviews'] = $reviews;
			$response_data['success'] = true;

			$response_data = apply_filters( 'cbxmcratingreview_dashboard_listing_data', $response_data );

			$response->set_data( $response_data );

			return $response;
		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );

			return $response;
		}
	}//end method getLatestListingData

	/**
	 * Full plugin option reset
	 *
	 * @since 2.0.0
	 */
	public function pluginOptionsReset( \WP_REST_Request $request ) {

		$response = new \WP_REST_Response();
		$response->set_status( 200 );

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized', 'cbxmcratingreview' ) );
			}

			if ( ! current_user_can( 'cbxmcratingreview_settings_manage' ) ) {
				throw new Exception( esc_html__( 'Sorry, you don\'t have enough permission!', 'cbxmcratingreview' ) );
			}


			$data = $request->get_params();

			do_action( 'cbxmcratingreview_plugin_reset_before' );

			//delete options
			$reset_options = isset( $data['reset_options'] ) ? $data['reset_options'] : [];

			foreach ( $reset_options as $key => $option ) {
				if ( $option ) {
					delete_option( $key );
				}
			}

			do_action( 'cbxmcratingreview_plugin_option_delete' );
			do_action( 'cbxmcratingreview_plugin_reset_after' );
			do_action( 'cbxmcratingreview_plugin_reset' );

			$response->set_data( [
				'success' => true,
				'info'    => esc_html__( 'Setting options reset successfully', 'cbxmcratingreview' )
			] );

			return $response;
		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );

			return $response;
		}
	} //end plugin_reset

	/**
	 * Full plugin option reset
	 *
	 * @since 2.0.0
	 */
	public function runMigration( \WP_REST_Request $request ) {
		$response = new \WP_REST_Response();
		$response->set_status( 200 );

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized', 'cbxmcratingreview' ) );
			}

			if ( ! current_user_can( 'cbxmcratingreview_settings_manage' ) ) {
				throw new Exception( esc_html__( 'Sorry, you don\'t have enough permission!', 'cbxmcratingreview' ) );
			}

			MigrationManage::run();

			do_action( 'cbxmcratingreview_manual_migration_run_after' );

			$response->set_data( [
				'success' => true,
				'info'    => esc_html__( 'Migrated successfully', 'cbxmcratingreview' )
			] );

			return $response;
		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );

			return $response;
		}
	} //end runMigration
}//end class DashboardController