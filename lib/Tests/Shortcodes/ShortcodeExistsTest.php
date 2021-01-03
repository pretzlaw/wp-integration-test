<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AssertionsTest.php
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
 * @since      2020-01-10
 */

namespace Pretzlaw\WPInt\Tests\Shortcodes;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Tests\ShortcodesTestCase;
use Pretzlaw\WPInt\Traits\ShortcodeAssertions;
use Pretzlaw\WPInt\WPAssert;

/**
 * Assert that a shortcode exists
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class ShortcodeExistsTest extends ShortcodesTestCase
{
    public function testAssertShortcodeExistsThrowsErrorWhenNot()
    {
        $this->expectException(AssertionFailedError::class);
        WPAssert::assertShortcodeExists(uniqid());
    }

    public function testCanAssertShortcodeExists()
    {
        WPAssert::assertShortcodeExists('gallery');
    }

    public function testAssertShortcodeNotExistsThrowsErrorWhenItDoes()
    {
        $this->expectException(AssertionFailedError::class);
        WPAssert::assertShortcodeNotExists('caption');
    }

    public function testCanAssertThatShortcodeDoesNotExists()
    {
        WPAssert::assertShortcodeNotExists(uniqid());
    }

}