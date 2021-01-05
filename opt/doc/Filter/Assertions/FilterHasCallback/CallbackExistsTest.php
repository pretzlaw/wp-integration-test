<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * CallbackExistsTest.php
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

use ArrayObject;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsEqual;
use Pretzlaw\WPInt\Constraint\FilterHasCallback;
use Pretzlaw\WPInt\Test\Filter\Assertions\FilterHasCallbackTestCase;

/**
 * CallbackExistsTest
 *
 * @covers \Pretzlaw\WPInt\Constraint\FilterHasCallback
 */
class CallbackExistsTest extends FilterHasCallbackTestCase
{
	protected function compatSetUp()
	{
		parent::compatSetUp();

		add_filter($this->filterName, $this->getCallback(), $this->priority);
	}

	public function compatTearDown()
	{
		remove_filter($this->filterName, $this->getCallback(), $this->priority);
		static::assertFilterNotHasCallback($this->filterName, $this->getCallback());

		parent::compatTearDown();
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\ActionAssertions::assertActionHasCallback()
	 * @covers \Pretzlaw\WPInt\Traits\ActionAssertions::getActionHooks()
	 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::assertFilterHasCallback()
	 */
	public function testAssertionFindsCallback()
	{
		static::assertFilterHasCallback($this->filterName, new IsEqual($this->getCallback()));
		self::assertActionHasCallback($this->filterName, new IsEqual($this->getCallback()));
	}

	/**
	 *
	 * @covers \Pretzlaw\WPInt\Traits\ActionAssertions::assertActionNotHasCallback()
	 * @covers \Pretzlaw\WPInt\Traits\ActionAssertions::getActionHooks()
	 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::assertFilterNotHasCallback()
	 */
	public function testAssertionInvertedNotFindsCallback()
	{
		// This should succeed
		static::assertFilterNotHasCallback($this->filterName, new ArrayObject());
		$this->assertActionNotHasCallback($this->filterName, new ArrayObject());

		$this->expectException(AssertionFailedError::class);

		static::assertFilterNotHasCallback($this->filterName, $this->getCallback());
	}

	/**
	 * @covers \Pretzlaw\WPInt\Constraint\FilterHasCallback
	 * @group unit
	 */
	public function testCallbackFoundByConstraint()
	{
		$constraint = new FilterHasCallback(new IsEqual($this->getCallback()), $this->filterName);

		static::assertTrue($constraint->evaluate(self::getWpHooks(), '', true));
	}
}
