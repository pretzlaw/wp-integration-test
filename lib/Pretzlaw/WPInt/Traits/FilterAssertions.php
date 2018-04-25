<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\AssertionFailedError;

trait FilterAssertions {
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
