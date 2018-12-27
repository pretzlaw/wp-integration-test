<?php

namespace Pretzlaw\WPInt\Constraint;


use PHPUnit\Framework\Constraint\Constraint;

abstract class WpHookExists extends Constraint
{
    /**
     * @var array|\WP_Hook[]
     */
    protected $list;

    public function __construct($list = null)
    {
        parent::__construct();

        $this->list = $list;
    }

    protected function matches($filterName): bool
    {
        return (bool)$this->getWpHook($filterName);
    }

    /**
     * @param string $filterName
     * @return \WP_Hook|array|null
     */
    final protected function getWpHook(string $filterName)
    {
        $list = $this->getList();

        if (null === $list || !array_key_exists($filterName, $list)) {
            return null;
        }

        return $list[$filterName];
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

    /**
     * @return array|null
     */
    abstract protected function getList();
}