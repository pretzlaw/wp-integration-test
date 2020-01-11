<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ExistsTest.php
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
 * @copyright  2020 Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @since      2020-01-10
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Widgets;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Tests\AssertionTestCase;
use Pretzlaw\WPInt\Tests\WidgetsTestCase;
use Pretzlaw\WPInt\WPAssert;
use WP_Widget;

/**
 * Widget with specific base id is (not) registered
 *
 * @copyright  2020 Pretzlaw (https://rmp-up.de)
 */
class ExistsTest extends WidgetsTestCase implements AssertionTestCase
{
    public static function testValidation()
    {
        WPAssert::assertWidgetExists('my_own_widget');
    }

    public function testValidationFailsWhenNotTrue()
    {
        $baseId = uniqid();

        static::expectException(AssertionFailedError::class);
        static::expectExceptionMessage('Failed asserting that Widget with base-id "' . $baseId . '" is registered');

        WPAssert::assertWidgetExists($baseId);
    }

    public function testValidationFailureMessageCanBeChanged()
    {
        $baseId = uniqid();

        static::expectException(AssertionFailedError::class);
        static::expectExceptionMessage('Nope ' . $baseId);

        WPAssert::assertWidgetExists($baseId, 'Nope ' . $baseId);
    }

    /**
     * LogicalNot part follows:
     */

    public function testNegation()
    {
        WPAssert::assertWidgetNotExists(uniqid());
    }

    public function testNegatingFailsWhenInvalid()
    {
        static::expectException(AssertionFailedError::class);
        static::expectExceptionMessage('Failed asserting that Widget with base-id "my_own_widget" is not registered');

        WPAssert::assertWidgetNotExists('my_own_widget');
    }


    public function testNegationFailureMessageCanBeChanged()
    {
        static::expectException(AssertionFailedError::class);
        static::expectExceptionMessage('Nopedinope');

        WPAssert::assertWidgetNotExists('my_own_widget', 'Nopedinope');
    }
}