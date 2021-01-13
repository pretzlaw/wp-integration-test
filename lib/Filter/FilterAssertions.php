<?php

declare(strict_types=1);

namespace Pretzlaw\WPInt\Filter;

use Exception;
use Mockery\Expectation;
use Mockery\ExpectationInterface;
use Mockery\HigherOrderMessage;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\FilterEmpty;
use Pretzlaw\WPInt\Constraint\FilterHasCallback;
use Pretzlaw\WPInt\Mocks\Filter;
use Prophecy\Prophecy\MethodProphecy;
use WP_Hook;

const FILTER_WAS_EMPTY = -1;

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
        } catch (Exception $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    /**
     * @return WP_Hook[]|array[]
     */
    protected static function getWpHooks()
    {
        global $wp_filter;

        return (array) $wp_filter;
    }

    protected static function getWpHooksCallbacks(string $name): array
    {
        $hooks = self::getWpHooks();

        if (false === array_key_exists($name, $hooks)) {
            return [];
        }

        $callbacks = $hooks[$name];
        if (class_exists(WP_Hook::class) && $callbacks  instanceof WP_Hook) {
            $callbacks = $callbacks->callbacks;
        }

        return (array) $callbacks;
    }

    public static function assertFilterHasCallback($filter, $expectedCallback, $priority = null)
    {
        try {
            static::assertThat(static::getWpHooks(), new FilterHasCallback($expectedCallback, $filter, $priority));
        } catch (Exception $e) {
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
     * @param string|array|null $filterName
     */
    public function truncateFilter($filterNames = null)
    {
        if (null === $filterNames) {
            global $wp_filter;

            $filterNames = array_keys($wp_filter);
        }

        if (is_scalar($filterNames)) {
            $filterNames = [$filterNames];
        }

		global $wp_filter;
        $this->wpIntApply(new Filter\TruncateFilter($filterNames, $wp_filter));
	}

	/**
	 * Mock or assert the execution of a filter
	 *
	 * @param string $filterName
	 * @param int $priority
	 *
	 * @return ExpectationInterface|Expectation|HigherOrderMessage
	 */
	protected function mockFilter(string $filterName, int $priority = 10)
	{
		return $this->wpIntApply(
			new Filter($filterName, $priority)
		);
	}

	protected function disableAllCallbacksMatching($pattern)
	{
		global $wp_filter;
		$this->wpIntApply(
			new DisableAllCallbacksMatching($pattern, $wp_filter)
		);
	}
}
