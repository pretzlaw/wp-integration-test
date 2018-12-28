<?php

namespace Pretzlaw\WPInt\Constraint;

/**
 * Check for empty filters
 *
 * @package Pretzlaw\WPInt\Constraint
 */
class FilterEmpty extends WpHookEmpty
{
    /**
     * @return array|null
     */
    protected function getList()
    {
        if (null === $this->list) {
            global $wp_filter;
            return $wp_filter;
        }

        return $this->list;
    }
}