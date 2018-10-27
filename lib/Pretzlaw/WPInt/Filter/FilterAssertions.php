<?php

namespace Pretzlaw\WPInt\Filter;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;

/**
 * Assert that filter look as expected.
 *
 * @package Pretzlaw\WPInt\Traits
 */
trait FilterAssertions {
	public static function assertFilterNotHasCallback( $filter, $callback ) {
		global $wp_filter;

		if ( ! $wp_filter ) {
			throw new AssertionFailedError( 'Filter have not yet been initialized' );
		}

		if ( ! \array_key_exists( $filter, $wp_filter ) ) {
			// Having the filter not yet present is okay too.
			static::assertThat(
				$wp_filter,
				new LogicalNot( new ArrayHasKey( $filter ) )
			);

			return;
		}

		if ( false === $wp_filter[ $filter ] instanceof \WP_Hook ) {
			// Having the filter not yet present is okay too.
			static::assertThat(
				$wp_filter[ $filter ],
				new LogicalNot( new IsInstanceOf( \WP_Hook::class ) )
			);

			return;
		}

		static::assertThat(
			$wp_filter[ $filter ]->has_filter( $filter, $callback ),
			new IsFalse(),
			sprintf( 'Unexpected callback "%s" registered for "%s" filter.', $callback, $filter )
		);
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