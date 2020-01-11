<?php

namespace Pretzlaw\WPInt\Constraint;


use ArrayAccess;

abstract class WpHookHasCallback extends MatchesConstraint
{
    /**
     * @var string
     */
    private $hookName;

    public function __construct($constraint, string $hookName)
    {
        parent::__construct($constraint);

        $this->hookName = $hookName;
    }

    protected function matches($hooks): bool
    {
        if (!$hooks
            || false === array_key_exists($this->hookName, $hooks)
            || (!is_array($hooks[$this->hookName])
                && false === $hooks[$this->hookName] instanceof ArrayAccess
            )
        ) {
            return false;
        }

        foreach ($hooks[$this->hookName] as $priority => $registered) {
            foreach ($registered as $listener) {
                if (parent::matches($listener['function'])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return 'contains a constraint';
    }

    protected function failureDescription($other): string
    {
        return sprintf('Failed asserting that the "%s" hook', $this->hookName) . ' ' . $this->toString();
    }
}