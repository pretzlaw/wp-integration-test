<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\ActionEmpty;
use Pretzlaw\WPInt\Constraint\ActionHasCallback;

trait ActionAssertions {
    public static function assertActionHasCallback($action, $constraint, $message = '')
    {
        static::assertThat(static::getActionHooks(), new ActionHasCallback($constraint, $action), $message);
    }

    public function assertActionNotHasCallback($action, $constraint, $message = '')
    {
        static::assertThat(static::getActionHooks(), new LogicalNot(new ActionHasCallback($constraint, $action)), $message);
    }

    public static function assertActionNotEmpty($action, string $message = null)
    {
        static::assertThat($action, new LogicalNot(new ActionEmpty()), (string) $message);
    }

    /**
     * @since 0.2.0
     *
     * @param string $action The action name to check.
     * @param string $message Message in case of error.
     */
    public function assertActionEmpty(string $action, string $message = null)
    {
        static::assertThat($action, new ActionEmpty(), (string) $message);
    }

    /**
     * @return \WP_Hook[]
     * @internal
     */
    protected static function getActionHooks()
    {
        global $wp_filter;

        return (array) $wp_filter;
    }
}