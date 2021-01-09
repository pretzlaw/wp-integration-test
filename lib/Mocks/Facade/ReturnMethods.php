<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ReturnMethods.php
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

namespace Pretzlaw\WPInt\Mocks\Facade;

use Mockery\Expectation;

trait ReturnMethods
{
	/**
	 * @var Expectation
	 */
	protected $expectation;

	public function andReturn(...$args)
	{
		$this->expectation->andReturn(...$args);

		return $this;
	}

	public function andReturnArg(int $index)
	{
		$this->expectation->andReturnArg($index);

		return $this;
	}

	public function andReturnFalse()
	{
		$this->expectation->andReturnFalse();

		return $this;
	}

	public function andReturnNull()
	{
		$this->expectation->andReturnNull();

		return $this;
	}

	public function andReturnSelf()
	{
		$this->expectation->andReturnSelf();

		return $this;
	}

	public function andReturnTrue()
	{
		$this->expectation->andReturnTrue();

		return $this;
	}

	public function andReturnUndefined()
	{
		$this->expectation->andReturnUndefined();

		return $this;
	}

	public function andReturnUsing(...$callable)
	{
		$this->expectation->andReturnUsing(...$callable);

		return $this;
	}

	public function andReturnValues(array $values)
	{
		$this->expectation->andReturnValues($values);

		return $this;
	}

	public function andReturns(...$arguments)
	{
		$this->expectation->andReturns(...$arguments);

		return $this;
	}
}
