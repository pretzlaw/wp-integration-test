<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * UnregisterWidgetTest.php
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

namespace Pretzlaw\WPInt\Test\Widget\WidgetExists;

use Pretzlaw\WPInt\Constraint\Widget\ContainsWidgetBaseId;
use Pretzlaw\WPInt\Mocks\Widget\UnregisterWidget;
use Pretzlaw\WPInt\Test\Widget\WidgetExistsTestCase;

/**
 * UnregisterWidgetTest
 *
 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::getWidgetFactory()
 */
class UnregisterWidgetTest extends WidgetExistsTestCase
{
	private $unregisterWidget;
	/**
	 * @var ContainsWidgetBaseId
	 */
	private $widgetExists;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->unregisterWidget = new UnregisterWidget($this->widgetFactory->widgets, $this->widgetIdBase);
		$this->widgetExists = new ContainsWidgetBaseId($this->widgetIdBase);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Mocks\Widget\UnregisterWidget
	 *
	 * @group unit
	 */
	public function testMockWillRemoveWidgetFromList()
	{
		static::assertTrue($this->widgetExists->evaluate($this->widgetFactory->widgets, '', true));

		$this->unregisterWidget->apply();

		static::assertFalse($this->widgetExists->evaluate($this->widgetFactory->widgets, '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Mocks\Widget\UnregisterWidget
	 *
	 * @group unit
	 */
	public function testMockWillRecoverWidgetAfterTest()
	{
		$this->testMockWillRemoveWidgetFromList();

		static::assertFalse($this->widgetExists->evaluate($this->widgetFactory->widgets, '', true));

		$this->unregisterWidget->__invoke();

		static::assertTrue($this->widgetExists->evaluate($this->widgetFactory->widgets, '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\WidgetAssertions::unregisterWidgetsById
	 */
	public function testWidgetWillBeRemovedFromList()
	{
		static::assertWidgetExists($this->widgetIdBase);

		$this->unregisterWidgetsById($this->widgetIdBase);

		static::assertWidgetNotExists($this->widgetIdBase);
	}

	protected function compatTearDown()
	{
		$this->unregisterWidget->__invoke();

		parent::compatTearDown();
	}
}
