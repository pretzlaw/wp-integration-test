<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains mock class for WordPress cache
 *
 * LICENSE: This source file is created by the company around Mike Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package   pretzlaw/wp-integration-test
 * @copyright 2021 M. Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Mocks;

use Mockery;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;
use WP_Object_Cache;

/**
 * Mock data in the cache
 *
 * This class just fulfills the MockObject interface
 * to remove the mocked cache after each test run.
 *
 * @copyright 2021 M. Pretzlaw (https://rmp-up.de)
 *
 * @method InvocationMocker method($constraint)
 */
class Cache implements CleanUpInterface, ApplicableInterface
{
	/**
	 * @var mixed|WP_Object_Cache
	 */
	private $backup;
	/**
	 * @var WP_Object_Cache
	 */
	private $cache;

	/**
	 * Cache constructor.
	 *
	 * @param WP_Object_Cache|null $cache
	 */
	public function __construct(&$cache)
	{
		$this->cache =& $cache;
		$this->backup = $cache;
	}

	public function apply()
	{
		// Default to global object cache
		if (false === is_object($this->cache)) {
			$this->cache = new WP_Object_Cache();
		}

		$mock = Mockery::mock($this->cache);
		$mock->makePartial();

		$this->cache = $mock;

		return $mock;
	}

	public function __invoke()
	{
		$this->cache = $this->backup;
	}
}
