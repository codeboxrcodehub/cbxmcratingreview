'use strict';

//https://gist.github.com/gfazioli/da51e93f0bb208a6f71f#file-wp_actions_filters-js

if ( typeof window.WPDK_FILTERS === 'undefined' ) {

	// List of filters
	window.CBXSCRATINGREVIEW_FILTERS = {};

	// List of actions
	window.CBXSCRATINGREVIEW_ACTIONS = {};

	/**
	 * Used to add an action or filter. Internal use only.
	 *
	 * @param {string}   type             Type of hook, 'action' or 'filter'.
	 * @param {string}   tag              Name of action or filter.
	 * @param {Function} function_to_add  Function hook.
	 * @param {integer}  priority         Priority.
	 *
	 * @since 1.6.1
	 */
	window._CBXscRatingReviewEvents_add = function( type, tag, function_to_add, priority )
	{
		var lists = ( 'filter' == type ) ? CBXSCRATINGREVIEW_FILTERS : CBXSCRATINGREVIEW_ACTIONS;

		// Defaults
		priority = ( priority || 10 );

		if( !( tag in lists ) ) {
			lists[ tag ] = [];
		}

		if( !( priority in lists[ tag ] ) ) {
			lists[ tag ][ priority ] = [];
		}

		lists[ tag ][ priority ].push( {
			func : function_to_add,
			pri  : priority
		} );

	};

	/**
	 * Hook a function or method to a specific filter action.
	 *
	 * WPDK offers filter hooks to allow plugins to modify various types of internal data at runtime in a similar
	 * way as php `add_filter()`
	 *
	 * The following example shows how a callback function is bound to a filter hook.
	 * Note that $example is passed to the callback, (maybe) modified, then returned:
	 *
	 * <code>
	 * function example_callback( example ) {
   * 	// Maybe modify $example in some way
   * 	return example;
   * }
	 * add_filter( 'example_filter', example_callback );
	 * </code>
	 *
	 * @param {string}   tag             The name of the filter to hook the function_to_add callback to.
	 * @param {Function} function_to_add The callback to be run when the filter is applied.
	 * @param {integer}  priority        Optional. Used to specify the order in which the functions
	 *                                   associated with a particular action are executed. Default 10.
	 *                                   Lower numbers correspond with earlier execution,
	 *                                   and functions with the same priority are executed
	 *                                   in the order in which they were added to the action.
	 * @return {boolean}
	 */
	window.CBXscRatingReviewEvents_add_filter = function( tag, function_to_add, priority )
	{
		_CBXscRatingReviewEvents_add( 'filter', tag, function_to_add, priority );
	};

	/**
	 * Hooks a function on to a specific action.
	 *
	 * Actions are the hooks that the WPDK core launches at specific points during execution, or when specific
	 * events occur. Plugins can specify that one or more of its Javascript functions are executed at these points,
	 * using the Action API.
	 *
	 * @since 1.6.1
	 *
	 * @uses _CBXscRatingReviewEvents_add() Adds an action. Parameter list and functionality are the same.
	 *
	 * @param {string}   tag             The name of the action to which the $function_to_add is hooked.
	 * @param {Function} function_to_add The name of the function you wish to be called.
	 * @param {integer}  priority        Optional. Used to specify the order in which the functions associated with a
	 *                                   particular action are executed. Default 10.
	 *                                   Lower numbers correspond with earlier execution, and functions with the same
	 *                                   priority are executed in the order in which they were added to the action.
	 *
	 * @return bool Will always return true.
	 */
	window.CBXscRatingReviewEvents_add_action = function( tag, function_to_add, priority )
	{
		_CBXscRatingReviewEvents_add( 'action', tag, function_to_add, priority );
	};

	/**
	 * Do an action or apply filters.
	 *
	 * @param {string} type Type of "do" to do 'action' or 'filter'.
	 * @param {Array} args Optional. Original list of arguments. This array could be empty for 'action'.
	 * @returns {*}
	 */
	window.CBXscRatingReviewEvents_do = function( type, args )
	{
		var hook, lists = ( 'action' == type ) ? CBXSCRATINGREVIEW_ACTIONS : CBXSCRATINGREVIEW_FILTERS;
		var tag = args[ 0 ];

		if( !( tag in lists ) ) {
			return args[ 1 ];
		}

		// Remove the first argument
		[].shift.apply( args );

		for( var pri in lists[ tag ] ) {

			hook = lists[ tag ][ pri ];

			if( typeof hook !== 'undefined' ) {

				for( var f in hook ) {
					var func = hook[ f ].func;

					if( typeof func === "function" ) {

						if( 'filter' === type ) {
							args[ 0 ] = func.apply( null, args );
						}
						else {
							func.apply( null, args );
						}
					}
				}
			}
		}

		if( 'filter' === type ) {
			return args[ 0 ];
		}

	};

	/**
	 * Call the functions added to a filter hook and the filtered value after all hooked functions are applied to it.
	 *
	 * The callback functions attached to filter hook $tag are invoked by calling this function. This function can be
	 * used to create a new filter hook by simply calling this function with the name of the new hook specified using
	 * the tag parameter.
	 *
	 * The function allows for additional arguments to be added and passed to hooks.
	 * <code>
	 * // Our filter callback function
	 * function example_callback( my_string, arg1, arg2 ) {
   *	// (maybe) modify my_string
   *	return my_string;
   * }
	 * CBXscRatingReviewEvents_add_filter( 'example_filter', example_callback, 10 );
	 *
	 * // Apply the filters by calling the 'example_callback' function we
	 * // "hooked" to 'example_filter' using the CBXscRatingReviewEvents_add_filter() function above.
	 * // - 'example_filter' is the filter hook tag
	 * // - 'filter me' is the value being filtered
	 * // - arg1 and arg2 are the additional arguments passed to the callback.
	 *
	 * var value = CBXscRatingReviewEvents_apply_filters( 'example_filter', 'filter me', arg1, arg2 );
	 * </code>
	 *
	 * @param {string} tag     The name of the filter hook.
	 * @param {*}      value   The value on which the filters hooked to <tt>tag</tt> are applied on.
	 * @param {...*}   varargs Optional. Additional variables passed to the functions hooked to <tt>tag</tt>.
	 *
	 * @return {*}
	 */
	window.CBXscRatingReviewEvents_apply_filters = function( tag, value, varargs )
	{
		return CBXscRatingReviewEvents_do( 'filter', arguments );
	};

	/**
	 * Execute functions hooked on a specific action hook.
	 *
	 * This function invokes all functions attached to action hook tag. It is possible to create new action hooks by
	 * simply calling this function, specifying the name of the new hook using the <tt>tag</tt> parameter.
	 *
	 * You can pass extra arguments to the hooks, much like you can with CBXscRatingReviewEvents_apply_filters().
	 *
	 * @since 1.6.1
	 *
	 * @param {string} tag  The name of the action to be executed.
	 * @param {...*}   args Optional. Additional arguments which are passed on to the functions hooked to the action.
	 *                      Default empty.
	 *
	 */
	window.CBXscRatingReviewEvents_do_action = function( tag, args )
	{
		CBXscRatingReviewEvents_do( 'action', arguments );
	};
}