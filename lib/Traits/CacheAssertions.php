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
 * @package   pretzlaw/wp-integration-test
 * @copyright 2021 M. Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Traits;

use Mockery\Mock;
use Pretzlaw\WPInt\Mocks\Cache;
use WP_Object_Cache;

/**
 * CacheAssertions
 *
 * @copyright  2021 M. Pretzlaw (https://rmp-up.de)
 */
trait CacheAssertions
{
	private $wpIntObjectCaches = [];

    /**
     * @return Mock|WP_Object_Cache
     */
    protected function mockCache(&$objectCache = null)
    {
		if (null === $objectCache) {
			$objectCache =& $GLOBALS['wp_object_cache'];
		}

		if (is_object($objectCache) && method_exists($objectCache, 'mockery_verify')) {
			// Already a mock which we will reuse.
			return $objectCache;
		}

		return $this->wpIntApply(new Cache($objectCache));
    }
}
