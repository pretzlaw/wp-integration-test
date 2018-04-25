<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\AssertionFailedError;

trait ActionAssertions {
	public static function assertActionHasCallback( $action, $expectedCallback ) {
		global $wp_filter;

		static::assertActionNotEmpty( $action );

		/** @var \WP_Hook $hook */
		$hook = $wp_filter[ $action ];

		static::assertNotFalse(
			$hook->has_filter( $expectedCallback ),
			sprintf( 'Expected callback not registered for "%s" action.', $action )
		);
	}

	public static function assertActionNotEmpty( $action ) {
		global $wp_filter;

		if ( ! array_key_exists( $action, $wp_filter ) || ! $wp_filter[ $action ]->has_filters() ) {
			throw new AssertionFailedError( sprintf( 'Action "%s" has no registered callbacks.', $action ) );
		}
	}
}
