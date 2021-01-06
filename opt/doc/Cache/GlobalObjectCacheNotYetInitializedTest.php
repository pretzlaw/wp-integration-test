<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * CreatesGlobalObjectCacheIfNotPresentTest.php
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
use Pretzlaw\WPInt\Traits\CacheAssertions;
use WP_Object_Cache;

/**
 * When the global object cache has not yet been initialized
 * then WP-Int will create it.
 *
 * @covers Pretzlaw\WPInt\Traits\CacheAssertions::mockCache()
 */
class GlobalObjectCacheNotYetInitializedTest extends CacheTestCase
{
	private $wpObjectCacheBackup;

	protected function compatSetUp()
	{
		$this->wpObjectCacheBackup = $GLOBALS['wp_object_cache'];

		$GLOBALS['wp_object_cache'] = null;
	}

	public function testGlobalObjectCacheWillBeCreated()
	{
		static::assertNull($GLOBALS['wp_object_cache']);

		$this->mockCache();

		static::assertInstanceOf(WP_Object_Cache::class, $GLOBALS['wp_object_cache']);
	}

	public function testGlobalObjectCacheResetToNullAfterTest()
	{
		static::assertNull($GLOBALS['wp_object_cache']);

		$this->mockCache();

		static::assertInstanceOf(WP_Object_Cache::class, $GLOBALS['wp_object_cache']);

		$this->wpIntCleanUp();

		static::assertNull($GLOBALS['wp_object_cache']);
	}

	public function compatTearDown()
	{
		// Pre-fire clean up so that our following recovery works
		$this->wpIntCleanUp();

		$GLOBALS['wp_object_cache'] = $this->wpObjectCacheBackup;

		parent::compatTearDown();
	}
}
