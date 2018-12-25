<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\PluginIsActive;

trait PluginAssertions {
    /**
     * @param string $plugin The plugin slug to check.
     * @param string $message Message to show on error.
     */
    protected function assertPluginIsActive(string $plugin, $message = '') {
        self::assertThat($plugin, new PluginIsActive(), $message);
    }

    /**
     * @param string $plugin The plugin slug to check.
     * @param string $message Message to show on error.
     */
    protected function assertPluginIsNotActive(string $plugin, $message = '') {
        self::assertThat($plugin, new LogicalNot(new PluginIsActive()), $message);
    }
}
