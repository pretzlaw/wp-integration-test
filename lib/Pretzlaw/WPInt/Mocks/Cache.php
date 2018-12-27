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

namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\MockObject\Invocation\ObjectInvocation;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pretzlaw\WPInt\ProxyInvocationBuilder;
use Pretzlaw\WPInt\ProxyMocker;

/**
 * Mock data in the cache
 *
 * This class just fulfills the MockObject interface
 * to remove the mocked cache after each test run.
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-27
 * @method InvocationMocker method($constraint)
 */
class Cache implements MockObject
{
    private $matcher = [];
    private $invocationMocker;
    /**
     * @var \WP_Object_Cache
     */
    private $proxyTarget;

    /**
     * Due to fifo-stack in PHPUnit we would destroy the global so we only store the very first one.
     * @var array
     */
    protected static $originalObjectCache;

    public function __construct($proxyTarget = null)
    {
        if (null === $proxyTarget) {
            global $wp_object_cache;
            $proxyTarget = $wp_object_cache;
        }

        $this->__phpunit_setOriginalObject($proxyTarget);
    }

    public function register(TestCase $testCase)
    {
        global $wp_object_cache;

        if (!static::$originalObjectCache) {
            static::$originalObjectCache = $wp_object_cache;
        }

        $wp_object_cache = $this;

        $testCase->registerMockObject($this);
    }

    /**
     * Adds a new matcher to the collection which can be used as an expectation
     * or a stub.
     *
     * @param Invocation $matcher Matcher for invocations to mock objects
     */
    public function addMatcher(Invocation $matcher)
    {
        $this->matcher[] = $matcher;
    }

    /**
     * Registers a new expectation in the mock object and returns the match
     * object which can be infused with further details.
     *
     * @param Invocation $matcher
     *
     * @return ProxyInvocationBuilder
     */
    public function expects(Invocation $matcher)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher);
    }

    public function __call($method, $arguments)
    {
        return $this->__phpunit_getInvocationMocker()->invoke(
            new ObjectInvocation(get_class($this), $method, $arguments, 'mixed', $this),
            $this->proxyTarget->get(...$arguments)
        );
    }

    /**
     * @return InvocationMocker
     */
    public function __phpunit_setOriginalObject($originalObject)
    {
        $this->proxyTarget = $originalObject;
    }

    /**
     * @return ProxyMocker
     */
    public function __phpunit_getInvocationMocker()
    {
        if (!$this->invocationMocker) {
            $this->invocationMocker = new ProxyMocker();
        }

        return $this->invocationMocker;
    }

    /**
     * Verifies that the current expectation is valid. If everything is OK the
     * code should just return, if not it must throw an exception.
     *
     * @throws ExpectationFailedException
     */
    public function __phpunit_verify()
    {
        $this->reset();

        return $this->__phpunit_getInvocationMocker()->verify();
    }

    /**
     * @return bool
     */
    public function __phpunit_hasMatchers()
    {
        return $this->__phpunit_getInvocationMocker()->hasMatchers();
    }

    public function __destruct()
    {
        $this->reset();
    }

    public function reset()
    {
        global $wp_object_cache;

        if (static::$originalObjectCache) {
            // Only reset if we have a history ready.
            $wp_object_cache = static::$originalObjectCache;
        }
    }
}