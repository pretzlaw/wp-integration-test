<?php

namespace Pretzlaw\WPInt\Constraint;


use ArrayAccess;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

abstract class WpHookHasCallback extends MatchesConstraint
{
    /**
     * @var string
     */
    private $hookName;
    /**
     * @var null|Constraint
     */
    private $priorityLevel;

    public function __construct($constraint, string $hookName, $priorityLevel = null)
    {
        parent::__construct($constraint);

        $this->hookName = $hookName;

        if (null !== $priorityLevel && false === $priorityLevel instanceof Constraint) {
            $priorityLevel = new IsEqual($priorityLevel);
        }

        $this->priorityLevel = $priorityLevel;
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
            if (null !== $this->priorityLevel && !$this->priorityLevel->evaluate($priority, '', true)) {
                continue;
            }

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
        $message = 'contains a constraint';

        if ($this->priorityLevel) {
            $message .= ' (where priority ' . $this->priorityLevel->toString() . ')';
        }

        return $message;
    }

    protected function failureDescription($other): string
    {
        return sprintf('the "%s" hook', $this->hookName) . ' ' . $this->toString();
    }
}
