<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MockTest.php
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

namespace Pretzlaw\WPInt\Test\Filter;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Test\TestCase;

/**
 * Mocking filter
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::mockFilter
 */
class MockTest extends TestCase
{
	private $filterName;

	protected $wpIntCleanUp = [];

	protected function compatSetUp()
	{
		$this->filterName = '_some_magic_filter_' . random_int(1, 99);
	}

	public function testCanCountMock()
	{
		$this->mockFilter($this->filterName)->once();

		apply_filters($this->filterName, null);
	}

	public function testWouldFailOnInvalidCount()
	{
		$this->expectException(AssertionFailedError::class);

		$this->mockFilter($this->filterName)->once();

		apply_filters($this->filterName, null);
		apply_filters($this->filterName, null);

		$this->wpIntPostConditions();
	}

	public function testCanMockFilter()
	{
		$expectedReturn = uniqid('', true);

		$this->mockFilter($this->filterName)->andReturn($expectedReturn);

		static::assertSame($expectedReturn, apply_filters($this->filterName, null));
	}

	public function testIgnoresOtherCalls()
	{
		$expectedReturn = uniqid('', true);

		$this->mockFilter($this->filterName)->with('bar')->andReturn($expectedReturn);

		static::assertNull(apply_filters($this->filterName, null));
		static::assertEquals($expectedReturn, apply_filters($this->filterName, 'bar'));
		static::assertEquals('untouched', apply_filters($this->filterName, 'untouched'));
	}

	public function testFilterIsRemovedAfterwards()
	{
		static::assertFilterEmpty($this->filterName);

		$this->mockFilter($this->filterName);
		$this->wpIntCleanUp();

		static::assertFilterEmpty($this->filterName);
	}
}
