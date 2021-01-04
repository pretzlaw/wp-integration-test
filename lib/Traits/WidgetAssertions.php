<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * WidgetTests.php
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

namespace Pretzlaw\WPInt\Traits;

use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\MockObject\MockObject;
use Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId;
use Pretzlaw\WPInt\Constraint\Widget\WidgetIsInstanceOf;
use Pretzlaw\WPInt\Mocks\BackupVariable;
use Pretzlaw\WPInt\Mocks\Recovery;
use Pretzlaw\WPInt\Mocks\Widget;
use WP_Widget;
use WP_Widget_Factory;

/**
 * WidgetTests
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
trait WidgetAssertions
{
    public static function assertWidgetExists($idBase, $message = '')
    {
        static::assertThat(
            (array) static::getWidgetFactory()->widgets,
            new ContainsWidgetBaseId($idBase),
            $message
        );
    }

    public static function assertWidgetIsInstanceOf(string $idBase, string $classOrInterface, $message = '')
    {
        static::assertThat(
            (array) static::getWidgetFactory()->widgets,
            new WidgetIsInstanceOf($idBase, $classOrInterface),
            $message
        );
    }

    public static function assertWidgetIsNotInstanceOf(string $idBase, string $classOrInterface, $message = '')
    {
        static::assertThat(
            (array) static::getWidgetFactory()->widgets,
            new LogicalNot(new WidgetIsInstanceOf($idBase, $classOrInterface)),
            $message
        );
    }

    public static function assertWidgetNotExists($idBase, $message = '')
    {
        static::assertThat(
            (array) static::getWidgetFactory()->widgets,
            new LogicalNot(new ContainsWidgetBaseId($idBase)),
            $message
        );
    }

    protected function backupWidgets()
    {
        $backup = new BackupVariable(static::getWidgetFactory()->widgets);

        $this->wpIntMocks[] = $backup;
    }

    /**
     * @param \WP_Widget|MockObject $widget
     * @param string                $idBase
     */
    public function mockWidget($widget)
    {
        if (false === $widget instanceof \WP_Widget && false === $widget instanceof MockObject) {
            throw new \InvalidArgumentException(
                '::mockWidget needs either a WP_Widget or MockObject instance'
            );
        }

        $registerTemporary = new Widget($widget);

        $this->registerMockObject($registerTemporary);
        $registerTemporary->register();
    }

    protected function unregisterAllWidgets()
    {
        $this->backupWidgets();

        static::getWidgetFactory()->widgets = [];
    }

    protected function unregisterWidgetsById(string $idBase)
    {
        $backup = [];
        foreach (static::getWidgetFactory()->widgets as $hash => $widget) {
            /** @var \WP_Widget $widget */
            if ($idBase === $widget->id_base) {
                $backup[$hash] = $widget;
                unset(static::getWidgetFactory()->widgets[$hash]);
            }
        }

        $recovery = new Recovery(static function () use ($backup) {
            foreach ($backup as $hash => $widget) {
                static::getWidgetFactory()->widgets[$hash] = $widget;
            }
        });

        $this->wpIntMocks[] = $recovery;
    }

    /**
     * Global instance of the widget factory
     *
     * @return WP_Widget_Factory
     */
    protected static function getWidgetFactory(): WP_Widget_Factory
    {
        /** @var WP_Widget_Factory $wp_widget_factory */
        global $wp_widget_factory;

        return $wp_widget_factory;
    }
}
