<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Api\CBXRoute;
use CBX\MCRatingReview\Controllers\FormController;
use CBX\MCRatingReview\Controllers\LogController;
use CBX\MCRatingReview\Controllers\LogFrontController;
use CBX\MCRatingReview\Controllers\AvgLogController;
use CBX\MCRatingReview\Controllers\DashboardController;

//forms routers
CBXRoute::middleware( 'cbxmcratingreview_form_manage' )->get( 'v1/form-list', [ FormController::class, 'getForms' ] );
CBXRoute::middleware( 'cbxmcratingreview_form_view' )->get( 'v1/form-data', [ FormController::class, 'getForm' ] );
CBXRoute::middleware( 'cbxmcratingreview_form_delete' )->post( 'v1/delete-form', [
	FormController::class,
	'deleteForm'
] );
CBXRoute::middleware( 'cbxmcratingreview_form_edit' )->post( 'v1/save-form', [ FormController::class, 'saveForm' ] );

//log routers
CBXRoute::middleware( 'cbxmcratingreview_log_manage' )->get( 'v1/log-list', [ LogController::class, 'getLogs' ] );
CBXRoute::middleware( 'cbxmcratingreview_log_view' )->get( 'v1/log-data', [ LogController::class, 'getLog' ] );
CBXRoute::middleware( 'cbxmcratingreview_log_delete' )->post( 'v1/delete-log', [ LogController::class, 'deleteLog' ] );
CBXRoute::middleware( 'cbxmcratingreview_log_edit' )->post( 'v1/save-log', [ LogController::class, 'saveLog' ] );

//avg routers
CBXRoute::middleware( 'cbxmcratingreview_log_manage' )->get( 'v1/avg-log-list', [
	AvgLogController::class,
	'getAvgLogs'
] );
CBXRoute::middleware( 'cbxmcratingreview_log_delete' )->post( 'v1/delete-avg-log', [
	AvgLogController::class,
	'deleteAvgLog'
] );

//frontend submit routes
CBXRoute::get( 'v1/user-log-list', [ LogFrontController::class, 'getPublicLogs' ] );
CBXRoute::get( 'v1/user-log-data', [ LogFrontController::class, 'getPublicLog' ] );
CBXRoute::post( 'v1/save-user-log', [ LogFrontController::class, 'savePublicLog' ] );


//misc routes
CBXRoute::middleware( 'manage_options' )->get( 'v1/dashboard-overview', [
	DashboardController::class,
	'getGlobalOverviewData'
] );
CBXRoute::middleware( 'manage_options' )->get( 'v1/dashboard-listing', [
	DashboardController::class,
	'getLatestListingData'
] );

CBXRoute::middleware( 'manage_options' )->post( 'v1/reset-option', [
	DashboardController::class,
	'pluginOptionsReset'
] );

CBXRoute::middleware( 'manage_options' )->post( 'v1/migrate-table', [
	DashboardController::class,
	'runMigration'
] );




