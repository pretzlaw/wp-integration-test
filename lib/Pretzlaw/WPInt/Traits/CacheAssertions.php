<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains trait for cache mocks.
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

namespace Pretzlaw\WPInt\Traits;

use Pretzlaw\WPInt\Mocks\Cache;

/**
 * CacheAssertions
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-27
 */
trait CacheAssertions
{
    private $wpCacheMock;
    /**
     * @return Cache
     */
    protected function mockCache(): Cache
    {
        $cache = new Cache();
        $cache->register($this);

        return $cache;
    }

    protected function mockCacheGet($cacheKey, $cacheData, $cacheGroup = '')
    {
        $this->mockCache()
            ->expects($this->any())
            ->method('get')
            ->with($cacheKey, $cacheGroup)
            ->willReturn($cacheData);
    }

    /**
     * Safely reset global state
     *
     * PHPUnit does no verify of mock objects when an assertion failed.
     * So we hook in the tear down process and assert the cleanup of the
     * WordPress cache mocks.
     *
     * @after
     */
    public function tearDownWpCacheMock()
    {
        global $wp_object_cache;

        if ($wp_object_cache instanceof Cache) {
            $wp_object_cache->reset();
        }
    }
}