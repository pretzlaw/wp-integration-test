<?php


namespace Pretzlaw\WPInt\Filter;


use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

/**
 * Helper for working with filters
 *
 * @package Pretzlaw\WPInt\Filter
 */
class FilterHelper {
	/**
	 * Remove callbacks mathing a pattern.
	 *
	 * A pattern can be defined using ...
	 *
	 * - primitives like string,
	 * - arrays
	 * - or phpUnit constraints.
	 *
	 * Of course you can combine them.
	 *
	 * ## Example
	 *
	 * ### Using phpUnit Constraints
	 *
	 * Removing a filter that makes problems with backupGlobals of phpUnit:
	 *
	 *      \Pretzlaw\WPInt\Filter\FilterHelper::removeAllCallbacksMatching(
	 *          [
	 *              new \PHPUnit\Framework\Constraint\IsInstanceOf( \Vc_Manager::class ),
	 *              new \PHPUnit\Framework\Constraint\IsAnything()
	 *          ]
	 *      );
	 *
	 *
	 * @param $pattern
	 *
	 * @throws \PHPUnit\Framework\Exception
	 */
	public static function removeAllCallbacksMatching( $pattern ) {
		global $wp_filter;

		// Ensure arrays starting at zero.
		$pattern = \array_values( (array) $pattern );

		foreach ( $pattern as $key => $value ) {
			if ( false === $value instanceof Constraint ) {
				$pattern[ $key ] = new IsEqual( $value );
			}
		}

		$patternCount = \count( $pattern );

		foreach ( $wp_filter as $filterName => $hook ) {
			/** @var \WP_Hook $hook */
			foreach ( $hook as $priority => $callbacks ) {
				foreach ( $callbacks as $callbackKey => $callback ) {
					// Ensure array keys starting at zero.
					$func = \array_values( (array) $callback['function'] );

					if ( \count( $func ) !== $patternCount ) {
						continue;
					}

					try {
						foreach ( $pattern as $key => $constaint ) {
							Assert::assertThat( $func[ $key ], $constaint );
						}
					} catch ( \Exception $e ) {
						continue;
					}

					// Delegate to WordPress for a clean environment.
					\remove_filter( $filterName, $callback['function'], $priority );
				}
			}
		}
	}
}