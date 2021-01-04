<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * UnregisterWidgetsById.php
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

namespace Pretzlaw\WPInt\Tests\Widgets\MockWidget;

use Pretzlaw\WPInt\Tests\MockTestCase;
use Pretzlaw\WPInt\Tests\WidgetsTestCase;
use Pretzlaw\WPInt\Traits\WordPressTests;
use Pretzlaw\WPInt\WPAssert;
use WP_Widget;

/**
 * UnregisterWidgetsById
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class UnregisterWidgetsByIdTest extends WidgetsTestCase implements MockTestCase
{
    use WordPressTests;

    /**
     * @var int
     */
    private $expectedCount;
    private $firstWidget;
    private $secondWidget;

    protected function setUp()
    {
        parent::setUp();

        $this->firstWidget = new WP_Widget('my-own-1', 'my-own-1');
        register_widget($this->firstWidget);

        $this->secondWidget = new WP_Widget('my-own-2', 'my-own-2');
        register_widget($this->secondWidget);

        $this->expectedCount = count(static::getWidgetFactory()->widgets);
        static::assertGreaterThan(0, $this->expectedCount);

        static::assertWidgetExists('my-own-1');
        static::assertWidgetExists('my-own-2');
    }

    protected function tearDown()
    {
        // Assert that it reappears afterwards
        static::assertCount($this->expectedCount, static::getWidgetFactory()->widgets);
        static::assertWidgetExists('my-own-1');
        static::assertWidgetExists('my-own-2');

        unregister_widget($this->firstWidget);
        unregister_widget($this->secondWidget);

        parent::tearDown();
    }

    public function testCoveredDuringTest()
    {
        static::assertCount($this->expectedCount, static::getWidgetFactory()->widgets);
        $this->unregisterWidgetsById('my-own-1');
        static::assertWidgetNotExists('my-own-1');
        static::assertCount($this->expectedCount - 1, static::getWidgetFactory()->widgets);

        $this->unregisterWidgetsById('my-own-2');
        static::assertWidgetNotExists('my-own-2');
        static::assertCount($this->expectedCount - 2, static::getWidgetFactory()->widgets);
    }

    public function testRecoverAfterTest()
    {
        static::assertCount($this->expectedCount, static::getWidgetFactory()->widgets);
        $this->unregisterWidgetsById('my-own-1');
        static::assertWidgetNotExists('my-own-1');
        static::assertCount($this->expectedCount - 1, static::getWidgetFactory()->widgets);
    }
}
