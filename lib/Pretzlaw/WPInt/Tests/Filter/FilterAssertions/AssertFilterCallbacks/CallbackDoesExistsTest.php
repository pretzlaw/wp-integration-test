<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks;


use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Tests\AllTraits;

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

        add_filter($this->targetFilter, '__return_true');
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
            sprintf('Failed asserting that \'__return_true\' does not exist in "%s" filter.', $this->targetFilter)
        );

        AllTraits::assertFilterNotHasCallback($this->targetFilter, '__return_true');
    }

    /**
     * assertFitlerHasCallback
     *
     * If the callback has been registered for the filter,
     * then it will pass the assertion.
     */
    public function testFilterHasCallbackSucceeds()
    {
        AllTraits::assertFilterHasCallback($this->targetFilter, '__return_true');
    }
}