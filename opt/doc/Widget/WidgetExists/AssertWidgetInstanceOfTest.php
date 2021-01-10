<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AssertWidgetInstanceOfTest.php
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

use ArrayObject;
use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Constraint\Widget\WidgetIsInstanceOf;
use Pretzlaw\WPInt\Test\Widget\WidgetExistsTestCase;
use WP_Widget;

/**
 * AssertWidgetInstanceOfTest
 */
class AssertWidgetInstanceOfTest extends WidgetExistsTestCase
{
	/**
	 * @covers \Pretzlaw\WPInt\Constraint\Widget\WidgetIsInstanceOf
	 * @group unit
	 */
	public function testConstraintWhichWidgetIsInstanceOfReturnsTrue()
	{
		$constraint = new WidgetIsInstanceOf($this->widgetIdBase, get_class($this->widgetMock));

		static::assertTrue($constraint->evaluate($this->widgetFactory->widgets, '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Constraint\Widget\WidgetIsInstanceOf
	 */
	public function testConstraintReturnsFalseForWrongInstance()
	{
		$constraint = new WidgetIsInstanceOf($this->widgetIdBase, ArrayObject::class);

		static::assertFalse($constraint->evaluate($this->widgetFactory->widgets, '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Constraint\Widget\WidgetIsInstanceOf
	 */
	public function testConstraintReturnsTrueForParentClass()
	{
		$constraint = new WidgetIsInstanceOf($this->widgetIdBase, WP_Widget::class);

		static::assertTrue($constraint->evaluate($this->widgetFactory->widgets, '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetIsInstanceOf
	 */
	public function testAssertionDoesSucceedForCorrectClass()
	{
		self::assertWidgetIsInstanceOf($this->widgetIdBase, WP_Widget::class);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetIsInstanceOf()
	 */
	public function testAssertionDoesFailForWrongClass()
	{
		$this->expectException(AssertionFailedError::class);

		self::assertWidgetIsInstanceOf($this->widgetIdBase, ArrayObject::class);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::assertWidgetIsNotInstanceOf()
	 */
	public function testAssertionNegationFailsForCorrectClass()
	{
		$this->expectException(AssertionFailedError::class);

		self::assertWidgetIsNotInstanceOf($this->widgetIdBase, WP_Widget::class);
	}
}
