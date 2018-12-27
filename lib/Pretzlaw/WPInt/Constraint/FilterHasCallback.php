<?php

namespace Pretzlaw\WPInt\Constraint;


use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

class FilterHasCallback extends WpHookHasCallback
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