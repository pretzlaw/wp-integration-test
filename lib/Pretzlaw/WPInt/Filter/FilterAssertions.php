<?php

namespace Pretzlaw\WPInt\Filter;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\FilterHasCallback;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;

/**
 * Assert that filter look as expected.
 *
 * @package Pretzlaw\WPInt\Traits
 */
trait FilterAssertions {
    /**
     * @param $filter
     * @param $callback
     *
     * @throws AssertionFailedError
     */
	public static function assertFilterNotHasCallback( $filter, $callback ) {
	    try {
	        $constraint = new LogicalNot(new FilterHasCallback($filter));
	        $constraint->evaluate($callback);
        } catch (\Exception $e) {
	        throw new AssertionFailedError($e->getMessage());
        }

	}

	public static function assertFilterHasCallback( $filter, $expectedCallback ) {
		global $wp_filter;

		static::assertFilterNotEmpty( $filter );

		/** @var \WP_Hook $hook */
		$hook = $wp_filter[ $filter ];

		static::assertNotFalse(
			$hook->has_filter( $expectedCallback ),
			sprintf( 'Expected callback not registered for "%s" filter.', $filter )
		);
	}

	public static function assertFilterNotEmpty( $filter ) {
		global $wp_filter;

		if ( ! array_key_exists( $filter, $wp_filter ) || ! $wp_filter[ $filter ]->has_filters() ) {
			throw new AssertionFailedError( sprintf( 'Filter "%s" has no registered callbacks.', $filter ) );
		}
	}

	/**
	 * @param string $filterName
	 *
	 * @return ExpectedFilter
	 */
	public function mockFilter( $filterName ) {
		$mock = new ExpectedFilter( $this, $filterName );

		$mock->addFilter();

		return $mock;
	}

	/**
	 * Removes all registered filter
	 *
	 * @todo This should not truncate them forever. Recover after each test.
	 *
	 * @param string $filterName
	 */
	public function truncateFilter( $filterName ) {
		global $wp_filter;

		if ( ! isset( $wp_filter[ $filterName ] ) ) {
			return;
		}

		$wp_filter[ $filterName ]->callbacks = [];
	}

}