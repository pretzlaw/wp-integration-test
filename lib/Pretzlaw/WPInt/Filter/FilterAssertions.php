<?php

namespace Pretzlaw\WPInt\Filter;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\FilterHasCallback;
use Pretzlaw\WPInt\Constraint\FilterEmpty;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;

/**
 * Assert that filter look as expected.
 *
 * @package Pretzlaw\WPInt\Traits
 */
trait FilterAssertions
{
    /**
     * @param $filter
     * @param $expectedCallback
     *
     * @throws AssertionFailedError
     */
    public static function assertFilterNotHasCallback($filter, $expectedCallback)
    {
        try {
            $constraint = new LogicalNot(new FilterHasCallback($filter));
            $constraint->evaluate($expectedCallback);
        } catch (\Exception $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    public static function assertFilterHasCallback($filter, $expectedCallback)
    {
        try {
            $constraint = new FilterHasCallback($filter);
            $constraint->evaluate($expectedCallback);
        } catch (\Exception $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    public static function assertFilterNotEmpty($filter, $message = '')
    {
        static::assertThat($filter, new LogicalNot(new FilterEmpty()), $message);
    }


    public static function assertFilterEmpty($filter, $message = '')
    {
        static::assertThat($filter, new FilterEmpty(), $message);
    }

    /**
     * @param string $filterName
     *
     * @return ExpectedFilter
     */
    public function mockFilter($filterName)
    {
        $mock = new ExpectedFilter($this, $filterName);

        $mock->addFilter();

        return $mock;
    }

    /**
     * Removes all registered filter
     *
     * @todo This should not truncate them forever. Recover after each test.
     *
     * @param string $filterName
     */
    public function truncateFilter($filterName)
    {
        global $wp_filter;

        if (!isset($wp_filter[$filterName])) {
            return;
        }

        $wp_filter[$filterName]->callbacks = [];
    }

}