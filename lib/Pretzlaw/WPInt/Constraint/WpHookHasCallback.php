<?php

namespace Pretzlaw\WPInt\Constraint;


use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

abstract class WpHookHasCallback extends WpHookExists
{
    /**
     * @var string
     */
    private $filterName;

    /**
     * FilterHasCallback constructor.
     * @param string $hookName Name of the action / filter / hook.
     * @param null $list
     */
    public function __construct($hookName, $list = null)
    {
        parent::__construct($list);

        $this->filterName = $hookName;
    }

    protected function matches($callback)
    {
        if (!parent::matches($this->filterName)) {
            return false;
        }

        $list = $this->getWpHook($this->filterName);

        if (
            !is_array($list) // WP < 4.7
            && (
                !class_exists('\\WP_Hook')
                || false === $list instanceof \WP_Hook
                || !is_array($list->callbacks)
            )
        ) {
            // Invalid data type
            return false;
        }

        return $this->searchCallback($callback);
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
        if (false === $callback instanceof Constraint) {
            $callback = new IsEqual($callback);
        }

        foreach ($this->getWpHook($this->filterName) as $perPriority) {
            foreach ($perPriority as $filter) {
                if ($callback->evaluate($filter['function'], '', true)) {
                    return true;
                }
            }
        }

        return false;
    }
}