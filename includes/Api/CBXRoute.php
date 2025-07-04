<?php
namespace CBX\MCRatingReview\Api;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WP_Error;

/**
 * Class CBXRoute
 * @since 1.0.0
 */
class CBXRoute {

	/**
	 * Routes uri here
	 * @var array
	 * @since 1.0.0
	 */
	private static array $routes = [];

	/**
	 * Route prefix
	 * @var string
	 * @since 1.0.0
	 */
	private static string $prefix = 'cbxmcratingreview';

	private static string $capability = '';

	public function __construct() {
		require_once CBXMCRATINGREVIEW_ROOT_PATH . 'includes/Api/routes.php';
	}//end of constructor

	/**
	 * Rest API prefix Set
	 *
	 * @param $prefix
	 *
	 * @return static
	 * @since 1.0.0
	 */
	public static function prefix( $prefix ) {
		self::$prefix = $prefix;

		return new static();
	}//end method init

/**
	 * @param string $capabilities
	 *
	 * @return static
	 * @since 1.0.0
	 */
	public static function middleware( $capabilities ) {
		self::$capability = $capabilities;

		return new static();
	}//end method check_permission

	/**
	 * Rest API get route
	 *
	 * @param $uri
	 * @param array $action
	 *
	 * @return CBXRoute
	 * @since 1.0.0
	 */
	public static function get( $uri, $action = [] ) {
		// set routes
		self::setRoutes( 'GET', $uri, $action );

		return new static();

	}//end method prefix

	/**
	 * Set Routes
	 *
	 * @param string $method
	 * @param string $uri
	 * @param array $action
	 *
	 * @return WP_Error|void
	 * @since 1.0.0
	 */
	private static function setRoutes( $method, $uri, $action = [] ) {
		$className  = $action[0];
		$methodName = $action[1];

		// create class instance
		$classInstance = new $className();

		static::$routes[] = [
			'namespace'  => self::$prefix,
			'method'     => $method,
			'uri'        => $uri,
			'action'     => [ $classInstance, $methodName ],
			'capability' => self::$capability
		];

		// reset capability
		self::$capability = '';
	}//end method middleware

	/**
	 * Rest API post route
	 *
	 * @param $uri
	 * @param array $action
	 *
	 * @return CBXRoute
	 * @since 1.0.0
	 */
	public static function post( $uri, $action = [] ) {
		// set routes
		self::setRoutes( 'POST', $uri, $action );

		return new static();
	}//end method get

	/**
	 * Initialize WP rest hooks for rest api
	 *
	 * @since 1.0.0
	 */
	public function init() {
		foreach ( self::$routes as $route ) {
			register_rest_route( $route['namespace'],
				$route['uri'], [
					[
						'methods'             => $route['method'],
						'callback'            => $route['action'],
						'permission_callback' => function () use ( $route ) {
							return $this->check_permission( $route['capability'] );
						}
					]
				]
			);
		}

		cbxmcratingreview_mailer();
	}//end method post

	/**
	 * Check user permission current route
	 *
	 * @param $capability
	 *
	 * @return bool
	 */
	private function check_permission( $capability ) {
		if ( empty( $capability ) ) {
			return true;
		}

		if ( is_string( $capability ) ) {
			return current_user_can( $capability );
		} else {
			return false;
		}
	}//end method setRoutes

}//end method CBXRoute