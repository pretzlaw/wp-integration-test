<?php

require_once 'vendor/autoload.php';

const WP_DEBUG = 1;

\Pretzlaw\WPInt\run_wp();

/**
 * Fixing PHPUnit stuff
 */

// Globals sometimes removed before bootstrap
foreach (\get_defined_vars() as $name => $value) {
    if (!isset($GLOBALS[$name])) {
        continue;
    }

    $$name = $GLOBALS[$name];
}

// Fatal error: Uncaught TypeError: date() expects parameter 2 to be int
// see vendor/phpunit/php-code-coverage/src/Report/Html/Facade.php
$_SERVER['REQUEST_TIME'] = \time();

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
