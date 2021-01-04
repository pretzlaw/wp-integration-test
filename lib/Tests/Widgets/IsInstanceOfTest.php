<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * IsInstanceOfTest.php
 *
 * LICENSE: This source file is created by the company around Mike Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package    wp-integration-test
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @since      2020-01-11
 */

namespace Pretzlaw\WPInt\Tests\Widgets;

use ArrayObject;
use IteratorAggregate;
use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Tests\AssertionTestCase;
use Pretzlaw\WPInt\Tests\WidgetsTestCase;
use Pretzlaw\WPInt\WPAssert;
use WP_Widget;

/**
 * IsInstanceOfTest
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class IsInstanceOfTest extends WidgetsTestCase implements AssertionTestCase
{
    public function testNegatingFailsWhenInvalid()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            sprintf(
                'Failed asserting that widget "%s" is not instance of WP_Widget',
                self::TEST_WIDGET
            )
        );

        WPAssert::assertWidgetIsNotInstanceOf(self::TEST_WIDGET, WP_Widget::class);
    }

    /**
     * @inheritDoc
     */
    public function testNegation()
    {
        WPAssert::assertWidgetIsNotInstanceOf(self::TEST_WIDGET, ArrayObject::class);
    }

    public function testNegationFailureMessageCanBeChanged()
    {
        $message = uniqid('', true);

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);

        WPAssert::assertWidgetIsNotInstanceOf(self::TEST_WIDGET, WP_Widget::class, $message);
    }

    public static function testValidation()
    {
        // Class
        WPAssert::assertWidgetIsInstanceOf(self::TEST_WIDGET, 'Some_Widget');

        // Parent
        WPAssert::assertWidgetIsInstanceOf(self::TEST_WIDGET, 'WP_Widget');

        // Interface
        WPAssert::assertWidgetIsInstanceOf(self::TEST_WIDGET, IteratorAggregate::class);
    }

    public function testValidationFailsWhenNotTrue()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            sprintf(
                'Failed asserting that widget "%s" is instance of ArrayObject',
                self::TEST_WIDGET
            )
        );

        WPAssert::assertWidgetIsInstanceOf(self::TEST_WIDGET, ArrayObject::class);
    }

    public function testValidationFailureMessageCanBeChanged()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Fail0r ' . __CLASS__);

        WPAssert::assertWidgetIsInstanceOf(self::TEST_WIDGET, ArrayObject::class, 'Fail0r ' . __CLASS__);
    }
}
