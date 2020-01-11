<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ShortcodeHasCallbackTest.php
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

use ArrayObject;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\StringContains;
use Pretzlaw\WPInt\Tests\ShortcodesTestCase;
use Pretzlaw\WPInt\WPAssert;
use WP_Widget;

/**
 * Shortcode has callback
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class ShortcodeHasCallbackTest extends ShortcodesTestCase
{
    protected static $shortcodeCallbackObject;
    protected static $shortcodeName;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$shortcodeName = preg_replace('/[A-Za-z]+/', '', __CLASS__);

        if (!shortcode_exists(static::$shortcodeName)) {
            static::$shortcodeCallbackObject = new \WP_Widget('', 'Foo');
            add_shortcode(static::$shortcodeName, [static::$shortcodeCallbackObject, 'widget']);
        }
    }

    public function testCallbackIsNestedConstraint()
    {
        WPAssert::assertShortcodeHasCallback([new IsInstanceOf(WP_Widget::class), 'widget'], static::$shortcodeName);
    }

    public function testCallbackIsNestedConstraintButFailsWhenNotMatched()
    {
        $this->expectException(AssertionFailedError::class);
        WPAssert::assertShortcodeHasCallback([new IsInstanceOf(ArrayObject::class), 'widget'], static::$shortcodeName);
    }

    public function testCallbackMatchUsingSimpleConstraint()
    {
        WPAssert::assertShortcodeHasCallback(new StringContains('mg_caption_shortcod'), 'caption');
    }

    public function testFailsWhenShortcodeNotHasScalarCallback()
    {
        $this->expectException(AssertionFailedError::class);
        WPAssert::assertShortcodeHasCallback(uniqid(), 'caption');
    }

    public function testShortcodeHasArrayCallback()
    {
        WPAssert::assertShortcodeHasCallback([static::$shortcodeCallbackObject, 'widget'], static::$shortcodeName);
    }

    public function testShortcodeHasArrayCallbackFailsWhenCallbackWrong()
    {
        $this->expectException(AssertionFailedError::class);
        WPAssert::assertShortcodeHasCallback([new \WP_Widget('', 'Bar'), 'widget'], static::$shortcodeName);
    }

    public function testShortcodeHasScalarCallback()
    {
        WPAssert::assertShortcodeHasCallback('img_caption_shortcode', 'caption');
    }
}