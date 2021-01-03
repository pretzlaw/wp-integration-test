<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks;


use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\StringContains;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\WPAssert;

/**
 * CallbackDoesExistTest
 *
 * @inheritdoc
 * @package Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks
 */
class CallbackDoesExistsTest extends AbstractTestCase
{
    /**
     * @var string
     */
    private $targetFilter;

    protected function setUp()
    {
        parent::setUp();

        $this->targetFilter = uniqid('', true);

        \add_filter($this->targetFilter, '__return_true', 5);
        \add_filter($this->targetFilter, [$this, 'toString'], 13);
    }

    /**
     * assertFilterNotHasCallback
     *
     * When a callback does exist then the assertion will throw an exception.
     *
     * @testdox ::assertFilterNotHasCallback succeeds
     */
    public function testAssertFilterNotHasCallbackFails()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            sprintf('Failed asserting that the "%s" hook does not contain a constraint.', $this->targetFilter)
        );

        WPAssert::assertFilterNotHasCallback($this->targetFilter, '__return_true');
    }

    /**
     * assertFitlerHasCallback
     *
     * If the callback has been registered for the filter,
     * then it will pass the assertion.
     */
    public function testFilterHasCallbackSucceeds()
    {
        WPAssert::assertFilterHasCallback($this->targetFilter, '__return_true');
    }

    public function testFilterHasConstraint()
    {
        WPAssert::assertFilterHasCallback(
            $this->targetFilter,
            [new IsInstanceOf(__CLASS__), new StringContains('oStrin')]
        );
    }

    public function testFilterWithinPriority()
    {
        WPAssert::assertFilterHasCallback(
            $this->targetFilter,
            [new IsInstanceOf(__CLASS__), new StringContains('oStrin')],
            13
        );
    }

    public function testFilterInDifferentPriority()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('hook contains a constraint (where priority is less than 10)');

        WPAssert::assertFilterHasCallback(
            $this->targetFilter,
            [new IsInstanceOf(__CLASS__), new StringContains('oStrin')],
            new LessThan(10)
        );
    }
}