<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * DefaultsToGlobalObjectCacheTest.php
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

namespace Pretzlaw\WPInt\Test\Cache;

use Mockery\Mock;
use Pretzlaw\WPInt\Test\CacheTestCase;
use WP_Object_Cache;

/**
 * DefaultsToGlobalObjectCacheTest
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 * @covers \Pretzlaw\WPInt\Traits\CacheAssertions::mockCache()
 */
class DefaultsToGlobalObjectCacheTest extends CacheTestCase
{
	private $objectCache;

	protected function compatSetUp()
	{
		global $wp_object_cache;

		if (false === $wp_object_cache instanceof WP_Object_Cache) {
			$wp_object_cache = new WP_Object_Cache();
		}
	}

	public function testOverridesGlobalObjectCacheWithMock()
	{
		static::assertInstanceOf(WP_Object_Cache::class, $GLOBALS['wp_object_cache']);

		$this->mockCache();

		$this->assertIsMockedObjectCache($GLOBALS['wp_object_cache']);

		global $wp_object_cache;
		$this->assertIsMockedObjectCache($wp_object_cache);
	}

	public function testRecoversGlobalObjectCacheAfterTest()
	{
		static::assertInstanceOf(WP_Object_Cache::class, $GLOBALS['wp_object_cache']);
		$this->mockCache();

		$this->assertIsMockedObjectCache($GLOBALS['wp_object_cache']);

		$this->wpIntCleanUp();

		global $wp_object_cache;
		self::assertEquals(WP_Object_Cache::class, get_class($GLOBALS['wp_object_cache']));
		self::assertEquals(WP_Object_Cache::class, get_class($wp_object_cache));
	}
}
