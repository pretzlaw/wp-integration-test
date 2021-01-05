<?php


namespace Pretzlaw\WPInt\Filter;


use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

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
	 * @param $pattern
	 *
	 * @throws \PHPUnit\Framework\Exception
	 */


}
