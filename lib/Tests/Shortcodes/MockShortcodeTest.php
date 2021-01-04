<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MockShortcodeTest.php
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
use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Mocks\Shortcode;
use Pretzlaw\WPInt\Tests\ShortcodesTestCase;
use Pretzlaw\WPInt\Traits\WordPressTests;
use Pretzlaw\WPInt\WPAssert;
use ReflectionClass;
use ReflectionMethod;

/**
 * MockShortcodeTest
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class MockShortcodeTest extends ShortcodesTestCase
{
    use WordPressTests;

    protected $shortcodeMock;

    protected function setUp()
    {
        parent::setUp();

        $this->shortcodeMock = new Shortcode('some_mocked_shortcode');

        // Assure that shortcode form previous test will be removed.
        static::assertShortcodeNotExists('some_mocked_shortcode');
    }

    protected function tearDown()
    {
        // Just in case
        remove_shortcode('some_mocked_shortcode');

        parent::tearDown();
    }

    public function testCanMockShortcode()
    {
        static::assertShortcodeNotExists('some_other_shortcode');
        $this->mockShortcode('some_other_shortcode');
        static::assertShortcodeExists('some_other_shortcode');
    }

    public function testCanTestShortcodeRunCount()
    {
        $this->mockShortcode('some_other_shortcode')->expects($this->exactly(3));

        do_shortcode('[some_other_shortcode]');
        do_shortcode('[some_other_shortcode]');
        do_shortcode('[some_other_shortcode]');

        $this->shortcodeMock->__phpunit_verify(true);
    }

    public function testFailsWhenRunCountNotMatched()
    {
        $this->expectException(ExpectationFailedException::class);

        $this->shortcodeMock->expects($this->once());
        $this->shortcodeMock->__phpunit_verify(true);
    }

    public function testWithArguments()
    {
        $this->mockShortcode('some_other_shortcode')->expects($this->once())->with(['x' => '42', 'y' => '1337']);

        do_shortcode('[some_other_shortcode x="42" y="1337"]');
        do_shortcode('[some_mocked_shortcode]');
    }

    public function testWithReturn()
    {
        $this->mockShortcode('some_other_shortcode')->expects($this->once())->willReturn('gonna be');
        static::assertEquals('this gonna be the output', do_shortcode('this [some_other_shortcode] the output'));
    }
}
