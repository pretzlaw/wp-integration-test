<?php

namespace Pretzlaw\WPInt\Filter;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\FilterHasCallback;
use Pretzlaw\WPInt\Constraint\FilterEmpty;
use Pretzlaw\WPInt\Mocks\Filter;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Assert that filter look as expected.
 *
 * @package Pretzlaw\WPInt\Traits
 */
trait FilterAssertions
{
    /**
     * Check if a filter doesn't have a callback.
     *
     * @param string           $filter
     * @param mixed|Constraint $expectedCallback
     * @param null             $priority
     */
    public static function assertFilterNotHasCallback($filter, $expectedCallback, $priority = null)
    {
        try {
            static::assertThat(
                static::getWpHooks(),
                new LogicalNot(new FilterHasCallback($expectedCallback, $filter, $priority))
            );
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

    public static function assertFilterHasCallback($filter, $expectedCallback, $priority = null)
    {
        try {
            static::assertThat(static::getWpHooks(), new FilterHasCallback($expectedCallback, $filter, $priority));
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

    /**
     * @param string $filterName
     * @param int    $priority
     *
     * @return MethodProphecy
     */
    protected function mockFilter(string $filterName, int $priority = 10)
    {
        /** @var ObjectProphecy|Filter $mock */
        $mock = $this->prophesize(Filter::class);

        $callback = [$mock->reveal(), 'apply_filters'];
        add_filter($filterName, $callback, $priority, PHP_INT_MAX);

        $this->wpIntCleanUp[] = static function () use ($filterName, $callback) {
            remove_filter($filterName, $callback);
        };

        // Otherwise return first argument
        $mock->apply_filters()->withArguments([Argument::cetera()])->willReturnArgument(0);

        return $mock->apply_filters();
    }
}