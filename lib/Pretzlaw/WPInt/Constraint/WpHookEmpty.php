<?php

namespace Pretzlaw\WPInt\Constraint;

/**
 * Check for empty filters
 *
 * @package Pretzlaw\WPInt\Constraint
 */
abstract class WpHookEmpty extends WpHookExists
{
    protected function matches($filterName): bool
    {
        $list = $this->getWpHook($filterName);

        if (!$list) {
            // Does not even exist or not initialized
            return true;
        }

        if ($list instanceof \WP_Hook) {
            return !$list->has_filters();
        }

        return 0 === \count($list);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return 'has callbacks registered';
    }
}