<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains a build-helper for matcher.
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

namespace Pretzlaw\WPInt;

use PHPUnit\Framework\MockObject\Builder\Identity;
use PHPUnit\Framework\MockObject\Builder\Stub;
use PHPUnit\Framework\MockObject\Matcher;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\Stub as BaseStub;
use PHPUnit\Framework\MockObject\Stub\MatcherCollection;

/**
 * Set-up parameter constraints and mock return value
 *
 * This is an intermediate object which helps you building stubs in the invocation matcher.
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-27
 */
class ProxyInvocationBuilder implements Stub
{
    /**
     * @var MatcherCollection
     */
    private $collection;
    /**
     * @var Matcher
     */
    private $matcher;

    public function __construct(ProxyMocker $collection, Invocation $invocation)
    {
        $this->collection = $collection;
        $this->matcher = new Matcher($invocation);

        $this->collection->addMatcher($this->matcher);
    }

    /**
     * Sets the identification of the expectation to $id.
     *
     * @note The identifier is unique per mock object.
     *
     * @param string $id Unique identification of expectation.
     * @return ProxyInvocationBuilder
     */
    public function id($id)
    {
        $this->collection->registerId($id, $this);

        return $this;
    }

    /**
     * Stubs the matching method with the stub object $stub. Any invocations of
     * the matched method will now be handled by the stub instead.
     *
     * @param BaseStub $stub
     *
     * @return Identity
     */
    public function will(BaseStub $stub)
    {
        $this->matcher->setStub($stub);
    }

    public function willReturn($value)
    {
        $this->will(new BaseStub\ReturnStub($value));
    }

    public function with(...$arguments)
    {
        $this->matcher->setParametersMatcher(new Matcher\Parameters($arguments));

        return $this;
    }

    public function method(string $string)
    {
        $this->matcher->setMethodNameMatcher(new Matcher\MethodName($string));

        return $this;
    }
}
