<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * WidgetTestCase.php
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

namespace Pretzlaw\WPInt\Tests;

use Exception;
use IteratorAggregate;
use Pretzlaw\WPInt\Traits\WidgetAssertions;
use Traversable;

/**
 * WidgetTestCase
 *
 * @copyright  2020 Pretzlaw (https://rmp-up.de)
 */
class WidgetsTestCase extends AbstractTestCase
{
    use WidgetAssertions;

    const TEST_WIDGET = 'my_own_widget';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        foreach (static::getWidgetFactory()->widgets as $widget) {
            /** @var \WP_Widget $widget */
            if (self::TEST_WIDGET === $widget->id_base) {
                // Widget already registered
                return;
            }
        }

        register_widget(new \Some_Widget(self::TEST_WIDGET, 'Foo'));
    }

    public static function tearDownAfterClass()
    {
        foreach (static::getWidgetFactory()->widgets as $widget) {
            /** @var \WP_Widget $widget */
            if (self::TEST_WIDGET === $widget->id_base) {
                unregister_widget($widget);
            }
        }

        parent::tearDownAfterClass();
    }
}