<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AssertWidgetExistTest.php
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

namespace Pretzlaw\WPInt\Test\Widget\WidgetDoesNotExist;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Test\Widget\WidgetDoesNotExistTestCase;

/**
 * AssertWidgetExistTest
 */
class AssertWidgetExistTest extends WidgetDoesNotExistTestCase
{
	/**
	 * @group unit
	 *
	 * @covers \Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId::matches()
	 */
	public function testConstraintIfWidgetExistsIsFalse()
	{
		static::assertFalse($this->containsWidgetBaseIdConstraint->evaluate([], '', true));
	}

	/**
	 * @group integration
	 *
	 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetExists()
	 */
	public function testAssertionIfWidgetExistsFails()
	{
		$this->expectException(AssertionFailedError::class);

		self::assertWidgetExists($this->someUndefinedWidgetBaseId);
	}

	/**
	 * @group integration
	 *
	 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetNotExists()
	 */
	public function testAssertionIfWidgetDoesNotExistSucceeds()
	{
		self::assertShortcodeNotExists($this->someUndefinedWidgetBaseId);
	}

	/**
	 * @group acceptance
	 *
	 * @covers \Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId::matches()
	 * @covers \Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId::toString()
	 * @covers \Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId::failureDescription()
	 */
	public function testAssertionIfWidgetExistHasErrorMessage()
	{
		$this->expectException(AssertionFailedError::class);
		$this->expectExceptionMessageRegExp(
			sprintf(
				'/Failed asserting that Widget with base-id [\'"]%s[\'"] is registered/',
				preg_quote($this->someUndefinedWidgetBaseId, '/')
			)
		);

		self::assertWidgetExists($this->someUndefinedWidgetBaseId);
	}
}
