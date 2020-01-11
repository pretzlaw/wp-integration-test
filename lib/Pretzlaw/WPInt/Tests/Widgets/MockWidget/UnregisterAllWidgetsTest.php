<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * UnregisterAllWidgetsTest.php
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
 * @since      2020-01-11
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Widgets\MockWidget;

use Pretzlaw\WPInt\Tests\WidgetsTestCase;
use WP_Widget;

/**
 * UnregisterAllWidgetsTest
 *
 * @copyright  2020 Pretzlaw (https://rmp-up.de)
 */
class UnregisterAllWidgetsTest extends WidgetsTestCase
{
    /**
     * @var int
     */
    private $expectedCount;

    protected function setUp()
    {
        parent::setUp();

        $this->expectedCount = count(static::getWidgetFactory()->widgets);
        static::assertWidgetExists(self::TEST_WIDGET);
    }

    public function testNewlyRegisteredWidgetsWillBeGoneAfterTest()
    {
        $baseId = 'testing_51_newly_registered';

        static::assertWidgetNotExists($baseId);

        $this->unregisterAllWidgets();

        register_widget(new WP_Widget($baseId, $baseId));

        static::assertWidgetExists($baseId);
        static::assertCount(1, static::getWidgetFactory()->widgets);
    }

    protected function tearDown()
    {
        // Assert that the mock has been recovered
        static::assertGreaterThan(0, $this->expectedCount);
        static::assertCount($this->expectedCount, static::getWidgetFactory()->widgets);

        parent::tearDown();
    }

    public function testUnregistersAllWidgets()
    {
        $this->unregisterAllWidgets();

        static::assertCount(0, static::getWidgetFactory()->widgets);
    }
}