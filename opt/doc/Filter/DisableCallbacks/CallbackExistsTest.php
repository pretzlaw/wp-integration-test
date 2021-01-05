<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * CallbackExistsTest.php
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

namespace Pretzlaw\WPInt\Test\Filter\DisableCallbacks;

use Pretzlaw\WPInt\Filter\DisableAllCallbacksMatching;
use Pretzlaw\WPInt\Filter\FakeFilter;
use Pretzlaw\WPInt\Test\Filter\Mock\DisableCallbacksTestCase;

/**
 * CallbackExistsTest
 *
 * @covers \Pretzlaw\WPInt\Filter\DisableAllCallbacksMatching
 * @covers \Pretzlaw\WPInt\Filter\FakeFilter
 */
class CallbackExistsTest extends DisableCallbacksTestCase
{
	/**
	 * @var DisableAllCallbacksMatching
	 */
	private $disableCallbackMatcher;
	/**
	 * @var array
	 */
	private $filterDefinition;
	/**
	 * @var string
	 */
	private $filterName;
	private $filterPriority;

	private function assertHasBeenRecovered($filters)
	{
		static::assertEquals('__return_false', $filters[$this->filterName][$this->filterPriority]['a']['function']);
	}

	/**
	 * @param $function
	 */
	private function assertHasBeenReplacedWithFakeFilter($filters)
	{
		static::assertInstanceOf(
			FakeFilter::class,
			$filters[$this->filterName][$this->filterPriority]['a']['function']
		);
	}

	protected function compatSetUp()
	{
		$this->filterName = uniqid('', true);
		$this->filterPriority = 10;
		$this->filterDefinition = [
			$this->filterName => [
				$this->filterPriority => [
					'a' => [
						'function' => '__return_false',
						'accepted_args' => 1,
					]
				]
			]
		];

		add_filter($this->filterName, '__return_false', $this->filterPriority);
	}

	public function compatTearDown()
	{
		remove_filter($this->filterName, '__return_false', $this->filterPriority);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::disableAllCallbacksMatching
	 */
	public function testAssertionReplacesCallback()
	{
		static::assertFalse(apply_filters($this->filterName, null));

		$this->disableAllCallbacksMatching('__return_false');

		static::assertEquals(42, apply_filters($this->filterName, 42));
	}

	/**
	 * @group unit
	 */
	public function testRecoversPreviousCallback()
	{
		$copy = $this->filterDefinition;

		$matcher = new DisableAllCallbacksMatching('__return_false', $copy);
		$matcher->apply();

		$this->assertHasBeenReplacedWithFakeFilter($copy);

		$matcher->__invoke();

		$this->assertHasBeenRecovered($copy);
	}

	/**
	 * @group unit
	 */
	public function testReplacesCallbackWithFakeFilter()
	{
		$copy = $this->filterDefinition;

		$matcher = new DisableAllCallbacksMatching('__return_false', $copy);
		$matcher->apply();

		static::assertInstanceOf(FakeFilter::class, $copy[$this->filterName][$this->filterPriority]['a']['function']);
	}
}
