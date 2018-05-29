<?php

require_once 'vendor/autoload.php';

const WP_DEBUG = 1;

\Pretzlaw\WPInt\run_wp();

foreach ( \get_defined_vars() as $name => $value ) {
	if ( ! isset( $GLOBALS[ $name ] ) ) {
		continue;
	}

	$$name = $GLOBALS[ $name ];
}
