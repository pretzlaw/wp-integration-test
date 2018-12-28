<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\ActionEmpty;
use Pretzlaw\WPInt\Constraint\ActionHasCallback;

trait ActionAssertions {
    public static function assertActionHasCallback($action, $expectedCallback, $message = '')
    {
        static::assertThat($expectedCallback, new ActionHasCallback($action), $message);
    }

    public function assertActionNotHasCallback($action, $expectedCallback, $message = '')
    {
        static::assertThat($expectedCallback, new LogicalNot(new ActionHasCallback($action)), $message);
    }

    public static function assertActionNotEmpty($action, string $message = '')
    {
        static::assertThat($action, new LogicalNot(new ActionEmpty()), $message);
    }

    /**
     * @since 0.2.0
     *
     * @param string $action The action name to check.
     * @param string $message Message in case of error.
     */
    public function assertActionEmpty(string $action, string $message = '')
    {
        static::assertThat($action, new ActionEmpty(), $message);
    }
}
