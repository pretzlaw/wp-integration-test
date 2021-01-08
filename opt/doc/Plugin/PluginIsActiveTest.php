<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PluginIsActiveTest.php
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
 * PluginIsActiveTest
 *
 * @covers \Pretzlaw\WPInt\Constraint\PluginIsActive
 */
class PluginIsActiveTest extends PluginTestCase
{
	/**
	 * @covers \Pretzlaw\WPInt\Constraint\PluginIsActive
	 * @group unit
	 */
	public function testConstraintCanDetermineIfPluginIsActive()
	{
		static::assertTrue($this->pluginConstraint->evaluate($this->activePluginSlug, '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\PluginAssertions::assertPluginIsActive()
	 * @group acceptance
	 */
	public function testCanCheckIfPluginIsActive()
	{
		// This test should not fail
		$this->assertPluginIsActive($this->activePluginSlug);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\PluginAssertions::assertPluginIsNotActive()
	 * @group acceptance
	 */
	public function testCheckingIfPluginIsNotActiveWillFail()
	{
		$this->expectException(AssertionFailedError::class);

		// This test should fail
		$this->assertPluginIsNotActive($this->activePluginSlug);
	}
}
