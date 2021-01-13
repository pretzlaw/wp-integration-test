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
 * @copyright  2021 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 */

namespace Pretzlaw\WPInt\Traits;

use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId;
use Pretzlaw\WPInt\Constraint\Widget\WidgetIsInstanceOf;
use Pretzlaw\WPInt\Mocks\BackupVariable;
use Pretzlaw\WPInt\Mocks\Widget;
use WP_Widget_Factory;

/**
 * WidgetTests
 *
 * @copyright  2021 M. Pretzlaw (https://rmp-up.de)
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
    	$this->wpIntApply(new BackupVariable(static::getWidgetFactory()->widgets));
    }

    protected function unregisterAllWidgets()
    {
        $this->backupWidgets();

        static::getWidgetFactory()->widgets = [];
    }

    protected function unregisterWidgetsById(string $idBase)
    {
    	$this->wpIntApply(
    		new Widget\UnregisterWidget(static::getWidgetFactory()->widgets, $idBase)
		);
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
