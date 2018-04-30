<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\LogicalNot;

/**
 * Assert that filter work as expected
 *
 * @package Pretzlaw\WPInt\Traits
 */
trait FilterAssertions {
	public static function assertFilterNotHasCallback( $filter, $callback ) {
		global $wp_filter;

		if ( ! $wp_filter ) {
			throw new AssertionFailedError( 'Could not load filter' );
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
			$wp_filter[ $filter ]->has_filter( $callback ),
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
}
