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

use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;
use Pretzlaw\WPInt\Helper\DefaultToken;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

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
	/**
	 * @var ObjectProphecy|self
	 */
	private $objectProphecy;
	/**
	 * @var int|null
	 */
	private $priority;

	/**
	 * Filter constructor.
	 */
	public function __construct(ObjectProphecy $objectProphecy, string $filterName, int $priority = null)
	{
		$this->objectProphecy = $objectProphecy;
		$this->filterName = $filterName;

		if (null === $priority) {
			$priority = 10;
		}

		$this->priority = $priority;
		$this->callback = [$objectProphecy->reveal(), 'apply_filters'];
	}

	public function apply()
	{
		// Otherwise return first argument
		$this->objectProphecy
			->apply_filters()
			->withArguments([Argument::cetera()])
			->willReturnArgument(0);

		add_filter($this->filterName, $this->callback, $this->priority, PHP_INT_MAX);

		/** @var MethodProphecy $method */
		$method = $this->objectProphecy->apply_filters();

		return $method->withArguments([new DefaultToken()]);
	}

	public function apply_filters($first = null)
    {
        return $first;
    }

    public function __invoke()
	{
		remove_filter($this->filterName, $this->callback);
	}
}
