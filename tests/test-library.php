<?php
/**
 * Tests for one-time action and filter hooks.
 *
 * @package SteveGrunwell\OneTimeCallbacks
 */

/**
 * Tests for One-time Callbacks.
 */
class LibraryTest extends WP_UnitTestCase {

	/**
	 * The last arguments passed to self::func_get_args().
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Clear out self::$args at the beginning of each test.
	 *
	 * @before
	 */
	public function reset() {
		$this->args = null;
	}

	public function test_action_callbacks_are_only_called_once() {
		$unique = uniqid();

		add_action_once( 'some_action', array( $this, 'func_get_args' ) );

		do_action( 'some_action', $unique );

		$this->assertEquals( $unique, $this->args[0], 'Expected to see the callback executed.' );

		// Reset and run it again.
		$this->args = false;

		do_action( 'some_action', $unique );

		$this->assertFalse( $this->args, 'The callback should not be called a second time.' );
	}

	public function test_passes_along_all_arguments_to_actions() {
		$unique = uniqid();

		add_action_once( 'some_action', array( $this, 'func_get_args' ), 10, 3 );

		do_action( 'some_action', 'foo', 'bar', $unique );

		$this->assertEquals(
			array( 'foo', 'bar', $unique ),
			$this->args,
			'Expected all arguments to be passed to the callback.'
		);
	}

	public function test_filter_callbacks_are_only_called_once() {
		$unique = uniqid();

		add_filter_once( 'some_filter', 'strrev' );

		$this->assertEquals(
			strrev( $unique ),
			apply_filters( 'some_filter', $unique ),
			'The callback should be applied on the first run.'
		);
		$this->assertEquals(
			$unique,
			apply_filters( 'some_filter', $unique ),
			'The filter should not be applied on subsequent runs.'
		);
	}

	public function test_passes_along_all_arguments_to_filters() {
		$unique = uniqid();

		add_filter_once( 'some_filter', array( $this, 'func_get_args' ), 10, 3 );

		$this->assertEquals(
			array( 'foo', 'bar', $unique ),
			apply_filters( 'some_filter', 'foo', 'bar', $unique ),
			'Expected all arguments to be passed to the callback.'
		);
	}

	/**
	 * A test callback that stores the value of func_get_args() in self::$args.
	 *
	 * @return array The value of self::$args.
	 */
	public function func_get_args() {
		$this->args = func_get_args();

		return $this->args;
	}
}
