<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * CallbackDoesNotExistTest.php
 *
 * LICENSE: This source file is created by the company around M. Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package   wp-integration-test
 * @copyright 2021 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Test\Filter\Assertions\FilterHasCallback;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsEqual;
use Pretzlaw\WPInt\Constraint\FilterHasCallback;
use Pretzlaw\WPInt\Test\Filter\Assertions\FilterHasCallbackTestCase;

/**
 * CallbackDoesNotExistTest
 *
 * @covers \Pretzlaw\WPInt\Constraint\FilterHasCallback
 * (extends)
 * @covers \Pretzlaw\WPInt\Constraint\WpHookHasCallback
 */
class CallbackDoesNotExistTest extends FilterHasCallbackTestCase
{
	/**
	 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::getWpHooks()
	 */
	public function testConstriantDoesNotFindCallback()
	{
		$constraint = new FilterHasCallback(new IsEqual($this->getCallback()), $this->filterName, $this->priority);

		static::assertFalse($constraint->evaluate(static::getWpHooks(), '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::assertFilterNotHasCallback()
	 */
	public function testNegatedAssertionDoesNotFindCallback()
	{
		static::assertFilterNotHasCallback($this->filterName, $this->getCallback());
	}

	/**
	 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::assertFilterHasCallback()
	 */
	public function testNormalAssertionDoesNotFindCallback()
	{
		$this->expectException(AssertionFailedError::class);

		static::assertFilterHasCallback($this->filterName, $this->getCallback());
	}
}
