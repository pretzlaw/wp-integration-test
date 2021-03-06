<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * WidgetExistsTestCase.php
 *
 * LICENSE: This source file is created by the company around M. Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package   wp-integration-test
 * @copyright 2021 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Test\Widget;

use Mockery;
use Pretzlaw\WPInt\Test\WidgetTestCase;
use ReflectionClass;
use WP_Widget;
use WP_Widget_Factory;

/**
 * WidgetExistsTestCase
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class WidgetExistsTestCase extends WidgetTestCase
{
	/**
	 * @var WP_Widget_Factory
	 */
	protected $widgetFactory;
	protected $widgetName;
	protected $widgetIdBase;

	/**
	 * @var WP_Widget|Mockery\MockInterface
	 */
	protected $widgetMock;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		/** @var WP_Widget_Factory $wp_widget_factory */
		global $wp_widget_factory;

		/** @var WP_Widget widgetMock */
		$this->widgetMock = Mockery::mock(WP_Widget::class);

		$this->widgetName = uniqid('', true);
		$this->widgetMock->name = $this->widgetName;

		$this->widgetIdBase = uniqid('', true);
		$this->widgetMock->id_base = $this->widgetIdBase;

		$wp_widget_factory->register($this->widgetMock);

		$this->widgetFactory = (new ReflectionClass(WP_Widget_Factory::class))->newInstanceWithoutConstructor();

		// Put among others
		$this->widgetFactory->register(Mockery::mock(WP_Widget::class)->makePartial());
		$this->widgetFactory->register($this->widgetMock);
		$this->widgetFactory->register(Mockery::mock(WP_Widget::class)->makePartial());
	}

	protected function compatTearDown()
	{
		unregister_widget($this->widgetMock);

		parent::compatTearDown();
	}
}
