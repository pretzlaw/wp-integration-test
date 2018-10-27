<?php

namespace Pretzlaw\WPInt\Constraint;


use PHPUnit\Framework\Constraint\Constraint;

class FilterExists extends Constraint
{
    /**
     * @var array|\WP_Hook[]
     */
    protected $list;

    public function __construct($list = null)
    {
        parent::__construct();

        if (null === $list) {
            global $wp_filter;
            $list = $wp_filter;
        }

        $this->list = (array)$list;
    }

    protected function matches($filterName)
    {
        if (!$this->list) {
            return false;
        }

        return array_key_exists($filterName, $this->list);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'exists';
    }
}