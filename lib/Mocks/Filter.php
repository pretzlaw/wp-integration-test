<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Filter.php
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

namespace Pretzlaw\WPInt\Mocks;

use Mockery;
use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;

/**
 * Filter
 *
 * Just a stub for Prophecy.
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class Filter implements CleanUpInterface, ApplicableInterface
{
	/**
	 * @var callable
	 */
	private $callback;
	/**
	 * @var string
	 */
	private $filterName;
	private $mock;
	/**
	 * @var int|null
	 */
	private $priority;

	/**
	 * Filter constructor.
	 */
	public function __construct(string $filterName, int $priority = null)
	{
		$this->filterName = $filterName;

		if (null === $priority) {
			$priority = 10;
		}

		$this->priority = $priority;
	}

	/**
	 * @return mixed|Mockery\Expectation|Mockery\ExpectationInterface|Mockery\HigherOrderMessage
	 */
	public function apply()
	{
		$this->mock = Mockery::mock(Filter::class);
		$this->mock->makePartial();
		$this->callback = [$this->mock, 'apply_filters'];

		$higherOrderMessage = $this->mock->shouldReceive('apply_filters');

		// in doubt
		//$this->mock->shouldReceive('apply_filters')->withAnyArgs()->andReturnArg(0);

		add_filter($this->filterName, $this->callback, $this->priority, PHP_INT_MAX);

		return $higherOrderMessage;
	}

	public function apply_filters($first = null)
	{
		return $first;
	}

	public function __invoke()
	{
		remove_filter($this->filterName, $this->callback);

		try {
			$this->mock->mockery_verify();
		} catch (Mockery\Exception\InvalidCountException $e) {
			throw new AssertionFailedError(
				sprintf(
					'Expected %s to be called %d time(s) but called %d time(s).',
					str_replace(
						'apply_filters(',
						'apply_filters("' . $this->filterName . '", ',
						(string) $e->getMethodName()
					),
					(int) $e->getExpectedCount(),
					(int) $e->getActualCount()
				),
				$e->getCode()
			);
		}
	}
}
