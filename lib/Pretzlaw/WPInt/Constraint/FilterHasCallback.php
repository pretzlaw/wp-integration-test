<?php

namespace Pretzlaw\WPInt\Constraint;


use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

class FilterHasCallback extends FilterExists
{
    /**
     * @var string
     */
    private $filterName;

    /**
     * FilterHasCallback constructor.
     * @param string $filterName
     * @param null $list
     */
    public function __construct($filterName, $list = null)
    {
        parent::__construct($list);

        $this->filterName = $filterName;
    }

    protected function matches($callback)
    {
        if (!parent::matches($this->filterName)) {
            return false;
        }

        if (!\array_key_exists($this->filterName, $this->list) || !$this->list[$this->filterName]) {
            return false;
        }

        if ($this->list[$this->filterName] instanceof \WP_Hook) {
            return false !== $this->list[$this->filterName]->has_filter($this->filterName, $callback);
        }

        if (is_array($this->list[$this->filterName])) {
            return $this->searchCallback($callback);
        }

        throw new \DomainException('Unknown system state');
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

    /**
     * Search in array
     *
     * Compatibility for WordPress 4.0 and other that do not use \WP_Hook .
     *
     * @param $callback
     * @return bool
     */
    private function searchCallback($callback)
    {
        $target = $this->list[$this->filterName];

        if (false === $callback instanceof Constraint) {
            $callback = new IsEqual($callback);
        }

        foreach ($target as $perPriority) {
            foreach ($perPriority as $filter) {
                if ($callback->evaluate($filter['function'], '', true)) {
                    return true;
                }
            }
        }

        return false;
    }
}