<?php

namespace Pretzlaw\WPInt\Constraint;

class FilterExists extends WpHookExists
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