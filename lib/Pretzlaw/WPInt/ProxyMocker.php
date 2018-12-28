<?php

namespace Pretzlaw\WPInt;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Invocation;
use PHPUnit\Framework\MockObject\Matcher\Invocation as MatcherInvocation;
use PHPUnit\Framework\MockObject\Invokable;
use PHPUnit\Framework\MockObject\Stub\MatcherCollection;

/**
 * MatcherCollection that ignores the order of matcher.
 *
 * Matcher are registered using the builder which specify the return for different inputs.
 * Within PHPUnit it is common that this fails with the very first exception
 * because it handles the invocation mocker / stubs as a fifo stack.
 * In some cases we don't mind about the order
 * and just want to change a part of the underlying mock / methods.
 * So we ask each matcher for a return value
 * and suppress the warnings until the end of the test.
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-27
 */
class ProxyMocker implements MatcherCollection, Invokable
{
    /**
     * @var MatcherInvocation[]
     */
    private $matcher = [];

    /**
     * Invokes the invocation object $invocation so that it can be checked for
     * expectations or matched against stubs.
     *
     * @param Invocation $invocation The invocation object passed from mock object
     *
     * @return mixed
     */
    public function invoke(Invocation $invocation, $fallback = null)
    {
        foreach ($this->matcher as $matcher) {
            try {
                if ($matcher->matches($invocation)) {
                    return $matcher->invoked($invocation);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $fallback;
    }

    /**
     * Checks if the invocation matches.
     *
     * @param Invocation $invocation The invocation object passed from mock object
     *
     * @return bool
     */
    public function matches(Invocation $invocation)
    {
        foreach ($this->matcher as $matcher) {
            if ($matcher->matches($invocation)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds a new matcher to the collection which can be used as an expectation
     * or a stub.
     *
     * @param MatcherInvocation $matcher Matcher for invocations to mock objects
     */
    public function addMatcher(MatcherInvocation $matcher)
    {
        $this->matcher[] = $matcher;
    }

    public function registerId($id, $matcher)
    {

    }

    /**
     * Verifies that the current expectation is valid. If everything is OK the
     * code should just return, if not it must throw an exception.
     *
     * @throws ExpectationFailedException
     */
    public function verify()
    {
        foreach ($this->matcher as $matcher) {
            $matcher->verify();
        }
    }

    /**
     * @param MatcherInvocation $matcher
     * @return ProxyInvocationBuilder
     */
    public function expects(MatcherInvocation $matcher)
    {
        return new ProxyInvocationBuilder(
            $this,
            $matcher
        );
    }

    public function hasMatchers()
    {
        return (bool) $this->matcher;
    }
}