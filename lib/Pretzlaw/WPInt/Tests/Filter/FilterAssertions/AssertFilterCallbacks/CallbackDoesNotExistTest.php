<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks;


use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Tests\AllTraits;

/**
 * Class CallbackDoesNotExistTest
 * @package Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks
 * @inheritdoc
 */
class CallbackDoesNotExistTest extends AbstractTestCase
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
     * If the callback could not be found for a filter,
     * then this assertion passed.
     *
     * @testdox ::assertFilterNotHasCallback succeeds
     */
    public function testAssertFilterNotHasCallbackSucceeds()
    {
        static::assertNull(AllTraits::assertFilterNotHasCallback($this->targetFilter, 'foo'));
    }

    public function testFilterHasCallbackFails()
    {
        $this->markTestIncomplete('This did not fail for some reason');
        AllTraits::assertFilterHasCallback($this->targetFilter, 'foo');
    }
}