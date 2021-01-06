<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MockCache.php
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

use Pretzlaw\WPInt\Mocks\Cache;
use Pretzlaw\WPInt\Test\CacheTestCase;
use WP_Object_Cache;

/**
 * MockCache
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 * @covers \Pretzlaw\WPInt\Mocks\Cache
 */
class MockCacheTest extends CacheTestCase
{
	/**
	 * @var WP_Object_Cache
	 */
	private $cache;

	private $cacheGroup;

	/**
	 * @var string
	 */
	private $cacheValue;

	/**
	 * @var \PHPUnit\Framework\MockObject\MockObject|WP_Object_Cache
	 */
	private $wpCache;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->cacheKey = uniqid('', true);
		$this->cacheValue = uniqid('', true);
		$this->cacheGroup = uniqid('', true);

		$this->cache = new WP_Object_Cache();
		$this->cache->set($this->cacheKey, $this->cacheValue, $this->cacheGroup);

		$this->wpCache = $this->createMock(WP_Object_Cache::class);
	}

	public function testDoesAddJustOneLayerAboveWpObjectCache()
	{
		global $wp_object_cache;

		$this->mockCache();
		$this->assertIsMockedObjectCache($wp_object_cache);

		$hash = spl_object_hash($wp_object_cache);

		$this->mockCache();
		static::assertSame($hash, spl_object_hash($wp_object_cache));
	}

	/**
	 * @group unit
	 */
	public function testMockCacheValue()
	{
		$cache = new Cache($this->wpCache);
		$mock = $cache->apply();

		$mock->shouldReceive('get')->with('baz')->andReturn(465);
		$mock->shouldReceive('get')->with('foo')->andReturn('123');

		static::assertEquals(false, $mock->get('bar'));
		static::assertEquals('123', $mock->get('foo'));
		static::assertEquals(465, $mock->get('baz'));

		$cache->__invoke();
	}
}
