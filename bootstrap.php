<?php

require_once 'vendor/autoload.php';

const WP_DEBUG = 1;

\Pretzlaw\WPInt\run_wp();

foreach (\get_defined_vars() as $name => $value) {
    if (!isset($GLOBALS[$name])) {
        continue;
    }

    $$name = $GLOBALS[$name];
}

/**
 * Doubles for testing
 */
class Some_Widget extends \WP_Widget implements IteratorAggregate
{
    public function getIterator()
    {
        return [];
    }
}