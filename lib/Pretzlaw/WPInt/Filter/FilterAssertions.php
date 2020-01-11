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
            static::assertThat(static::getWpHooks(), new LogicalNot(new FilterHasCallback($expectedCallback, $filter)));
        } catch (\Exception $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    /**
     * @return \WP_Hook[]|array[]
     */
    protected static function getWpHooks()
    {
        global $wp_filter;

        return (array) $wp_filter;
    }

    public static function assertFilterHasCallback($filter, $expectedCallback)
    {
        try {
            static::assertThat(static::getWpHooks(), new FilterHasCallback($expectedCallback, $filter));
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
        $mock = new ExpectedFilter($filterName);

        $this->registerMockObject($mock);
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

        if (class_exists('WP_Hook') && $wp_filter[$filterName] instanceof \WP_Hook) {
            $wp_filter[$filterName]->callbacks = [];
            return;
        }

        $wp_filter[$filterName] = [];
    }

}