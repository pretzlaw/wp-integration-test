<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\MockFilter;


use Pretzlaw\WPInt\Filter\FilterAssertions;
use Pretzlaw\WPInt\Tests\AbstractTestCase;

class GoneAfterTest extends AbstractTestCase
{
    use FilterAssertions;

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

        $this->mockFilter(static::$filterName)->expects($this->any());

        static::assertTrue(has_filter(static::$filterName));

        $this->verifyMockObjects();

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