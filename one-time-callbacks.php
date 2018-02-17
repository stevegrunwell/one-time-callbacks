<?php
/**
 * Plugin Name: One-time Callbacks
 * Plugin URI:  https://github.com/stevegrunwell/one-time-callbacks
 * Description: Enable WordPress actions and filter callbacks to be called exactly once.
 * Author:      Steve Grunwell
 * Author URI:  https://stevegrunwell.com
 * Version:     1.0.0
 *
 * @package SteveGrunwell\OneTimeCallbacks
 */

if ( ! function_exists( 'add_action_once' ) ) :
	/**
	 * Register an action to run exactly one time.
	 *
	 * The arguments match that of add_action(), but this function will also register a second
	 * callback designed to remove the first immediately after it runs.
	 *
	 * @param string   $hook     The action name.
	 * @param callable $callback The callback function.
	 * @param int      $priority Optional. The priority at which the callback should be executed.
	 *                           Default is 10.
	 * @param int      $args     Optional. The number of arguments expected by the callback function.
	 *                           Default is 1.
	 * @return bool Like add_action(), this function always returns true.
	 */
	function add_action_once( $hook, $callback, $priority = 10, $args = 1 ) {
		$singular = function () use ( $hook, $callback, $priority, $args, &$singular ) {
			call_user_func_array( $callback, func_get_args() );
			remove_action( $hook, $singular, $priority, $args );
		};

		return add_action( $hook, $singular, $priority, $args );
	}
endif;

if ( ! function_exists( 'add_filter_once' ) ) :
	/**
	 * Register a filter to run exactly one time.
	 *
	 * The arguments match that of add_filter(), but this function will also register a second
	 * callback designed to remove the first immediately after it runs.
	 *
	 * @param string   $hook     The action name.
	 * @param callable $callback The callback function.
	 * @param int      $priority Optional. The priority at which the callback should be executed.
	 *                           Default is 10.
	 * @param int      $args     Optional. The number of arguments expected by the callback.
	 *                           Default is 1.
	 * @return bool Like add_filter(), this function always returns true.
	 */
	function add_filter_once( $hook, $callback, $priority = 10, $args = 1 ) {
		$singular = function () use ( $hook, $callback, $priority, $args, &$singular ) {
			$value = call_user_func_array( $callback, func_get_args() );
			remove_filter( $hook, $singular, $priority, $args );

			return $value;
		};

		return add_filter( $hook, $singular, $priority, $args );
	}
endif;
