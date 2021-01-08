<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * OverrideOptionTest.php
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

namespace Pretzlaw\WPInt\Test\Option;

use Pretzlaw\WPInt\Test\OptionTestCase;

/**
 * OverrideOptionTest
 *
 * @covers \Pretzlaw\WPInt\Traits\OptionAssertions::mockOption()
 */
class ExistingOptionTest extends OptionTestCase
{
	private $optionValue;
	private $optionName;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->optionName = 'admin_email';

		static::assertNotEmpty(get_option($this->optionName));

		$this->optionValue = uniqid('', true) . '@example.org';

		$this->mockOption($this->optionName)->andReturn($this->optionValue);
	}

	/**
	 * @group acceptance
	 */
	public function testOptionIsOverriden()
	{
		static::assertEquals($this->optionValue, get_option($this->optionName));
	}
}
