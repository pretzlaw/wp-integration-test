<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ProxyToExistingCachesTest.php
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

namespace Pretzlaw\WPInt\Mocks\Cache;

use Pretzlaw\WPInt\Mocks\Cache;
use Pretzlaw\WPInt\Test\TestCase;
use WP_Object_Cache;

/**
 * ProxyToExistingCachesTest
 *
 * @covers \Pretzlaw\WPInt\Mocks\Cache
 */
class AccessingNonMockedFieldTest extends TestCase
{
	private $cacheMock;
	private $wpObjectCache;
	private $existingCacheKey;
	private $existingCacheValue;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->wpObjectCache = new WP_Object_Cache();
		$this->existingCacheKey = uniqid('', true);
		$this->existingCacheValue = uniqid('', true);
		$this->wpObjectCache->set($this->existingCacheKey, $this->existingCacheValue);

		$this->cacheMock = new Cache($this->wpObjectCache);
	}

	public function testUndefinedFieldsWillProxyToWpObjectCache()
	{
		$mock = $this->cacheMock->apply();
		$mockedKey = uniqid('', true);

		$mock->shouldReceive('get')->with($mockedKey)->andReturn(32);

		static::assertEquals($this->existingCacheValue, $mock->get($this->existingCacheKey));
		static::assertEquals(32, $mock->get($mockedKey));
		static::assertEquals($this->existingCacheValue, $mock->get($this->existingCacheKey));
	}
}
