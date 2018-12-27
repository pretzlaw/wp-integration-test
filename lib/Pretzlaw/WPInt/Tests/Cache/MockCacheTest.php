<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing cache mock.
 *
 * LICENSE: This source file is created by the company around Mike Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://mike-pretzlaw.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@mike-pretzlaw.de so we can mail you a copy.
 *
 * @package    pretzlaw/wp-integration-test
 * @copyright  2018 Mike Pretzlaw
 * @license    https://mike-pretzlaw.de/license-generic.txt
 * @link       https://project.mike-pretzlaw.de/pretzlaw/wp-integration-test
 * @since      2018-12-27
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Cache;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Pretzlaw\WPInt\Mocks\Cache;
use Pretzlaw\WPInt\Traits\CacheAssertions;

/**
 * Cache
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-27
 */
class MockCacheTest extends TestCase
{
    use CacheAssertions;

    public function testOverridesGlobalCacheDuringTest()
    {
        global $wp_object_cache;

        static::assertNotInstanceOf(Cache::class, $wp_object_cache);

        // Mock
        $cache = new Cache();
        $cache->register($this);

        static::assertInstanceOf(Cache::class, $wp_object_cache);

        $cache->__phpunit_verify();

        static::assertNotInstanceOf(Cache::class, $wp_object_cache);
    }

    public function testDelegatesToOriginal()
    {
        // Prefill
        wp_cache_set('foo', 'bar');
        static::assertEquals('bar', wp_cache_get('foo'));

        // Override
        $cache = new Cache();
        $cache->register($this);

        // Assert
        static::assertEquals('bar', wp_cache_get('foo'));

        $cache->__phpunit_verify();
    }

    public function testCanOverrideData()
    {
        wp_cache_set('foo', 'bar');
        static::assertEquals('bar', wp_cache_get('foo'));

        $this->mockCache()->expects($this->any())->method('get')->with('key', 'group')->willReturn(5);
        $this->mockCache()->expects($this->any())->method('get')->with('some', 'thing')->willReturn(42);

        static::assertEquals(42, wp_cache_get('some', 'thing'));
        static::assertEquals(5, wp_cache_get('key', 'group'));
        static::assertNotEquals(5, wp_cache_get('group', 'key'));
        static::assertEquals('bar', wp_cache_get('foo'));
    }

    public function testCanWatchForChanges()
    {
        $mockCache = new Cache();
        $mockCache->expects($this->once())->method('set')->with('foo');
        $mockCache->expects($this->once())->method('set')->with('bar', 3);

        wp_cache_set('foo', 1337);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Expectation failed for method name is equal to "set" when invoked 1 time(s).');

        $mockCache->__phpunit_verify();
    }
}