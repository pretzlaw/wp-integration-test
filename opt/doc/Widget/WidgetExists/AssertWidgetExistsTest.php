<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AssertWidgetExistsTest.php
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

namespace Pretzlaw\WPInt\Test\Widget\WidgetExists;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId;
use Pretzlaw\WPInt\Test\Widget\WidgetExistsTestCase;

/**
 * AssertWidgetExistsTest
 *
 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetExists
 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetNotExists
 */
class AssertWidgetExistsTest extends WidgetExistsTestCase
{
	/**
	 * @covers \Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId
	 * @group unit
	 */
	public function testConstraintThatWidgetExistsIsTrue()
	{
		$constraint = new ContainsWidgetBaseId($this->widgetIdBase);

		static::assertTrue($constraint->evaluate($this->widgetFactory, '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetExists
	 * @group integration
	 */
	public function testAssertionThatWidgetExistsDoesSucceed()
	{
		self::assertWidgetExists($this->widgetIdBase);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetNotExists
	 * @group integration
	 */
	public function testAssertionThatWidgetNotExistsThrowsError()
	{
		$this->expectException(AssertionFailedError::class);

		self::assertWidgetNotExists($this->widgetIdBase);
	}
}
