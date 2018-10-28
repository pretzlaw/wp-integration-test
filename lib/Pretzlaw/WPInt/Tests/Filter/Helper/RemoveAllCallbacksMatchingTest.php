<?php

namespace Pretzlaw\WPInt\Tests\Filter\Helper;


use PHPUnit\Framework\Constraint\IsInstanceOf;
use Pretzlaw\WPInt\Filter\FilterHelper;
use Pretzlaw\WPInt\Tests\AbstractTestCase;

/**
 * Class RemoveAllCallbacksMatchingTest
 * @package Pretzlaw\WPInt\Tests\Filter\Helper
 * @backupGlobals
 */
class RemoveAllCallbacksMatchingTest extends AbstractTestCase
{
    private $filterName;

    private $methodName;

    protected function setUp()
    {
        parent::setUp();

        $this->filterName = uniqid('', true);
        $this->methodName = 'getGroups';
        add_filter($this->filterName, [$this, $this->methodName]);
    }

    /**
     * @group integration
     */
    public function testRemovesUsingConstraintsTest()
    {
        static::assertEquals(10, has_filter($this->filterName, [$this, $this->methodName]));

        FilterHelper::removeAllCallbacksMatching([
            new IsInstanceOf(static::class),
            $this->methodName
        ]);

        static::assertFalse(has_filter($this->filterName, [$this, $this->methodName]));
    }

    /**
     * @group integration
     */
    public function testRemovesUsingObjects()
    {
        static::assertEquals(10, has_filter($this->filterName, [$this, $this->methodName]));

        FilterHelper::removeAllCallbacksMatching([$this, $this->methodName]);

        static::assertFalse(has_filter($this->filterName, [$this, $this->methodName]));
    }

    /**
     * @group integration
     */
    public function testItDoesNotRemoveWhenArgumentCountDiffers()
    {
        static::assertEquals(10, has_filter($this->filterName, [$this, $this->methodName]));
        FilterHelper::removeAllCallbacksMatching([$this]);
        static::assertEquals(10, has_filter($this->filterName, [$this, $this->methodName]));
    }
}