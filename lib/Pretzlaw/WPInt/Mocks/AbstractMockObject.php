<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AbstractMockObject.php
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
 * @package    wp-integration-test
 * @copyright  2020 Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @since      2020-01-10
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker as InvocationMockerBuilder;
use PHPUnit\Framework\MockObject\InvocationMocker;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * AbstractMockObject
 *
 * @copyright  2020 Pretzlaw (https://rmp-up.de)
 */
abstract class AbstractMockObject implements MockObject
{
    /**
     * @var array
     */
    protected $args;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var bool
     */
    protected $return;

    public function __construct(string $name, $return = true, array $args = [])
    {
        $this->name = $name;
        $this->return = $return;
        $this->args = $args;
    }

    /**
     * @return InvocationMocker
     */
    public function __phpunit_getInvocationMocker()
    {
        if (null === $this->invocationMocker) {
            $this->invocationMocker = new \PHPUnit\Framework\MockObject\InvocationMocker(
                [$this->name, strtolower($this->name)],
                true
            );
        }

        return $this->invocationMocker;
    }

    /**
     * @var InvocationMocker|null
     */
    protected $invocationMocker;

    /**
     * @return bool
     */
    public function __phpunit_hasMatchers()
    {
        return $this->__phpunit_getInvocationMocker()->hasMatchers();
    }

    /**
     * @return InvocationMocker
     */
    public function __phpunit_setOriginalObject($originalObject)
    {

    }

    public function __phpunit_setReturnValueGeneration(bool $returnValueGeneration)
    {

    }

    /**
     * Verifies that the current expectation is valid. If everything is OK the
     * code should just return, if not it must throw an exception.
     *
     * @throws ExpectationFailedException
     */
    public function __phpunit_verify(bool $unsetInvocationMocker = null)
    {
        if (null === $unsetInvocationMocker) {
            $unsetInvocationMocker = true;
        }

        if ($unsetInvocationMocker) {
            $this->remove();
        }

        try {
            $this->__phpunit_getInvocationMocker()->verify();
        } catch (ExpectationFailedException $e) {
            throw new ExpectationFailedException($this->fixExceptionMessage($e), $e->getComparisonFailure());
        }
    }

    /**
     * Registers a new expectation in the mock object and returns the match
     * object which can be infused with further details.
     *
     * @param Invocation $matcher
     *
     * @return InvocationMockerBuilder
     */
    public function expects(Invocation $matcher)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher)->method($this->name);
    }

    abstract protected function fixExceptionMessage(\Exception $e);

    abstract protected function register();

    abstract protected function remove();
}