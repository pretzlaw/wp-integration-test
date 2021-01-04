<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\MockFilter;


use Pretzlaw\WPInt\Filter\FilterAssertions;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\WordPressTests;

class GoneAfterTest extends AbstractTestCase
{
    use WordPressTests;

    /**
     * @var string
     */
    private static $filterName;

    /**
     * @throws \Throwable
     */
    public function testMocksSomething()
    {
        static::$filterName = uniqid('', true);

        static::assertFalse(has_filter(static::$filterName));

        $mock = new ExpectedFilter(static::$filterName);
        $mock->expects($this->any());
        $mock->addFilter();

        static::assertTrue(has_filter(static::$filterName));

        $mock->__phpunit_verify();

        static::assertFalse(has_filter(static::$filterName));
    }

    /**
     * @depends testMocksSomething
     */
    public function testRegistersItselfAsNewMockObject()
    {
        static::assertFalse(has_filter(static::$filterName));

        $this->mockFilter(static::$filterName)->expects($this->any());

        static::assertTrue(has_filter(static::$filterName));
    }

    /**
     * @depends testRegistersItselfAsNewMockObject
     */
    public function testIsGoneWhenEnteringAnotherTest()
    {
        static::assertFalse(has_filter(static::$filterName));
    }
}
