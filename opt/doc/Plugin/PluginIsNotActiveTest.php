<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PluginIsNotActiveTest.php
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

namespace Pretzlaw\WPInt\Test\Plugin;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Test\PluginTestCase;

/**
 * PluginIsNotActiveTest
 *
 * @covers \Pretzlaw\WPInt\Constraint\PluginIsActive
 */
class PluginIsNotActiveTest extends PluginTestCase
{
	private $inactivePluginSlug = '7-9/8472.php';

	/**
	 * @covers \Pretzlaw\WPInt\Constraint\PluginIsActive
	 */
	public function testConstraintNoticesInactivePlugin()
	{
		static::assertFalse(
			$this->pluginConstraint->evaluate($this->inactivePluginSlug, '', true)
		);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\PluginAssertions::assertPluginIsActive()
	 */
	public function testPluginIsActiveAssertionThrowsError()
	{
		$this->expectException(AssertionFailedError::class);

		$this->assertPluginIsActive($this->inactivePluginSlug);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\PluginAssertions::assertPluginIsNotActive()
	 */
	public function testPluginIsNotActiveAssertionSucceeds()
	{
		$this->assertPluginIsNotActive($this->inactivePluginSlug);
	}
}
