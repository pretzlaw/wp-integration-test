<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * FilterHasCallback.php
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

namespace Pretzlaw\WPInt\Test\Filter\Assertions;

use Pretzlaw\WPInt\Test\TestCase;
use WP_Hook;

/**
 * FilterHasCallback
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 * @covers \Pretzlaw\WPInt\Constraint\FilterHasCallback
 */
class FilterHasCallbackTestCase extends TestCase
{
	/**
	 * @var callable
	 */
	private $callback;

	/**
	 * @var WP_Hook[]
	 */
	protected $filter;
	/**
	 * @var string
	 */
	protected $filterName;
	/**
	 * @var int
	 */
	protected $priority;

	protected function compatSetUp()
	{
		global $wp_filter;
		$this->filter = $wp_filter;

		$this->filterName = uniqid('', true);
		$this->callback = $this->getCallback();
		$this->priority = random_int(1, 99);
	}

	protected function getCallback()
	{
		if (null === $this->callback) {
			$this->callback = new class {
				public function __invoke($first = null)
				{
					return $first;
				}
			};
		}

		return $this->callback;
	}
}
