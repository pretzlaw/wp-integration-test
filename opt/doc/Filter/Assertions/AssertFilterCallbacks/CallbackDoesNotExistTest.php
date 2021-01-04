<?php

namespace Pretzlaw\WPInt\Test\Filter\Assertions\AssertFilterCallbacks;


use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Test\TestCase;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\WPAssert;

/**
 * Class CallbackDoesNotExistTest
 * @package Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks
 * @inheritdoc
 */
class CallbackDoesNotExistTest extends TestCase
{
    /**
     * @var string
     */
    private $targetFilter;

    protected function compatSetUp()
    {
        parent::compatSetUp();

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
        static::assertNull(WPAssert::assertFilterNotHasCallback($this->targetFilter, 'foo'));
    }

    public function testFilterHasCallbackFails()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'the "' . $this->targetFilter . '" hook contains a constraint'
        );

        WPAssert::assertFilterHasCallback($this->targetFilter, 'foo');
    }
}