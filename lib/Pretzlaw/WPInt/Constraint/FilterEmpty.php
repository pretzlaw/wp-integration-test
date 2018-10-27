<?php

namespace Pretzlaw\WPInt\Constraint;

/**
 * Check for empty filters
 *
 * @package Pretzlaw\WPInt\Constraint
 */
class FilterEmpty extends FilterExists
{
    /**
     * @var array|\WP_Hook[]
     */
    protected $list;

    public function __construct($list = null)
    {
        parent::__construct($list);
    }

    protected function matches($filterName)
    {
        if (!parent::matches($filterName)) {
            // Does not even exist
            return true;
        }

        if (null === $this->list[$filterName]) {
            // Not proper initialized
            return true;
        }

        if ($this->list[$filterName] instanceof \WP_Hook) {
            return !$this->list[$filterName]->has_filters();
        }

        if (\is_array($this->list[$filterName])) {
            return 0 === \count($this->list[$filterName]);
        }

        // Neither WP_Hook nor an array - where are we?
        throw new \DomainException('Unknown system state');
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'has callbacks registered';
    }
}