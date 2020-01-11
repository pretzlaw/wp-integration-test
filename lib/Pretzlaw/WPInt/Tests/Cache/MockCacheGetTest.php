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
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package    pretzlaw/wp-integration-test
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @link       https://project.rmp-up.de/pretzlaw/wp-integration-test
 * @since      2018-12-27
 */

namespace Pretzlaw\WPInt\Tests\Cache;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Pretzlaw\WPInt\Mocks\Cache;
use Pretzlaw\WPInt\Traits\CacheAssertions;

/**
 * Cache
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-27
 */
class MockCacheGetTest extends TestCase
{
    use CacheAssertions;

    public function testOverrideCacheValue()
    {
        static::assertNotEquals(1337, wp_cache_get('foo'));
        static::assertNotEquals(1337, wp_cache_get('bar'));

        $this->mockCacheGet('foo', 1337);


        static::assertEquals(1337, wp_cache_get('foo'));
        static::assertNotEquals(1337, wp_cache_get('bar'));
    }

    public function testDifferentReturnPerGroup()
    {
        static::assertNotEquals(1337, wp_cache_get('foo', 'grp1'));
        static::assertNotEquals(42, wp_cache_get('foo', 'grp2'));
        static::assertNotEquals(13, wp_cache_get('foo'));

        $this->mockCacheGet('foo', 13);
        $this->mockCacheGet('foo', 42, 'grp2');
        $this->mockCacheGet('foo', 1337, 'grp1');

        static::assertEquals(1337, wp_cache_get('foo', 'grp1'));
        static::assertEquals(42, wp_cache_get('foo', 'grp2'));
        static::assertEquals(13, wp_cache_get('foo'));
    }
}