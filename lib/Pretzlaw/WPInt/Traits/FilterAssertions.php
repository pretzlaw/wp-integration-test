<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;

/**
 * Assert that filter work as expected
 *
 * @package Pretzlaw\WPInt\Traits
 *
 * @deprecated 1.0.0 Use \Pretzlaw\WPInt\Filter\FilterAssertions instead
 */
trait FilterAssertions {
	use \Pretzlaw\WPInt\Filter\FilterAssertions;
}
