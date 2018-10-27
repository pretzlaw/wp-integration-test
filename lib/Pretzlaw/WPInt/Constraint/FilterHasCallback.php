<?php

namespace Pretzlaw\WPInt\Constraint;


use PHPUnit\Framework\Constraint\Constraint;

class FilterHasCallback extends Constraint
{
    /**
     * @var string
     */
    private $filterName;
    /**
     * @var null|\WP_Hook[]
     */
    private $list;

    /**
     * FilterHasCallback constructor.
     * @param string $filterName
     * @param null $list
     */
    public function __construct($filterName, $list = null)
    {
        parent::__construct();

        if (null === $list) {
            global $wp_filter;

            if (null === $wp_filter) {
                throw new \InvalidArgumentException('Filter have not yet been initialized');
            }

            $list = $wp_filter;
        }

        $this->filterName = $filterName;
        $this->list = $list;
    }

    protected function matches($callback)
    {
        if (!\array_key_exists($this->filterName, $this->list)) {
            return false;
        }

        if (false === $this->list[$this->filterName] instanceof \WP_Hook) {
            return false;
        }

        return false !== $this->list[$this->filterName]->has_filter($this->filterName, $callback);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf('does not exist in "%s" filter', $this->filterName);
    }
}