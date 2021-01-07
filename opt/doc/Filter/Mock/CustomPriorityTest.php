<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * CustomPriorityTest.php
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

namespace Pretzlaw\WPInt\Test\Filter\Mock;

use Pretzlaw\WPInt\Test\Filter\MockTest;

/**
 * CustomPriorityTest
 *
 * `WP_Hook::remove_filter()` seems to have problems finding the mock,
 * when the priority is not the default (= 10).
 * We monitor such behaviour with this test.
 *
 * @internal
 * @covers \Pretzlaw\WPInt\Mocks\Filter
 */
class CustomPriorityTest extends MockTest
{
	/**
	 * Assert that mock is removed after test
	 *
	 * We need to assure that a mocked filter is removed after test
	 * even with the custom priority.
	 *
	 * @see WP_Hook::remove_filter()
	 *
	 * @group acceptance
	 *
	 * @internal
	 */
	public function testRemovesMockAfterTest()
	{
		$filter = uniqid('', true);
		$priority = random_int(11, PHP_INT_MAX);
		$expectedReturn = uniqid('', true);

		$this->mockFilter($filter, $priority)->andReturn($expectedReturn);

		static::assertEquals($expectedReturn, apply_filters($filter, null));

		$this->wpIntCleanUp();

		static::assertNull(apply_filters($filter, null));
	}
}
