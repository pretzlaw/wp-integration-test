<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MockWidget.php
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
use Pretzlaw\WPInt\Tests\MockTestCase;
use Pretzlaw\WPInt\Tests\WidgetsTestCase;
use Pretzlaw\WPInt\WPAssert;

/**
 * Mocking Widgets
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class MockWidgetTest extends WidgetsTestCase implements MockTestCase
{
    /**
     * @var string
     */
    private $baseId;

    protected function setUp()
    {
        parent::setUp();

        $this->baseId = 'test_mock_widget' . __LINE__;

        static::assertWidgetNotExists($this->baseId);
    }

    public function testRecoverAfterTest()
    {
        $widget = new \WP_Widget($this->baseId, 'Foo');

        $this->mockWidget($widget);
        WPAssert::assertWidgetExists($this->baseId);
    }

    protected function tearDown()
    {
        WPAssert::assertWidgetNotExists($this->baseId);

        parent::tearDown();
    }

    public function testCoveredDuringTest()
    {
        $widget = new \WP_Widget($this->baseId, 'Foo');

        $this->mockWidget($widget);

        WPAssert::assertWidgetExists($this->baseId);
    }

    public function testThrowsExceptionForInvalidArguments()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('WP_Widget or MockObject instance');

        $this->mockWidget(new ArrayObject());
    }
}