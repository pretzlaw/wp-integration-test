<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AbstractMockStack.php
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
 * @copyright 2020 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\InvocationMocker;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * AbstractMockStack
 *
 * @copyright 2020 Pretzlaw (https://rmp-up.de)
 * @method \PHPUnit\Framework\MockObject\Builder\InvocationMocker method($constraint)
 */
abstract class AbstractMockObjectStack implements MockObject
{
    /**
     * @var MockObject[]
     */
    private $stack = [];

    public function __construct(array $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @return InvocationMocker
     */
    public function __phpunit_getInvocationMocker()
    {
        if (null === $this->invocationMocker) {
            $this->invocationMocker = new \PHPUnit\Framework\MockObject\InvocationMocker([], true);
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
        return false === empty($this->stack);
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
        foreach ($this->stack as $mock) {
            $mock->__phpunit_verify($unsetInvocationMocker);
        }
    }

    public function expects(Invocation $matcher)
    {
        throw new \RuntimeException('This mock has no ::expects() capabilities');
    }
}