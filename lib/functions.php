<?php

namespace Pretzlaw\WPInt;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use function file_exists;
use const DIRECTORY_SEPARATOR;

function prepare_env() {
	$serverVars = [
		'SERVER_PROTOCOL' => 'HTTP/1.0',
		'HTTP_USER_AGENT' => '',
		'REQUEST_METHOD'  => 'GET',
		'REMOTE_ADDR'     => '127.0.0.1',
	];

	if ( ! isset( $_SERVER ) ) {
        $_SERVER = [];
	}

	$_SERVER = array_merge( $serverVars, $_SERVER );
}

/**
 * @param $path
 *
 * @throws Exception
 */
function load_wp( $path ) {
	$wpLoad = $path . DIRECTORY_SEPARATOR . 'wp-blog-header.php';

	if ( ! file_exists( $wpLoad ) ) {
        throw new Exception( 'Missing wp-blog-header.php to load WordPress' );
	}

	require_once $wpLoad;
}

/**
 * @param null $path
 *
 * @throws Exception
 */
function run_wp( $path = null ) {
	\Pretzlaw\WPInt\prepare_env();

	if ( null === $path ) {
        $path = locate_wordpress();
	}

	load_wp( $path );
}

/**
 * @throws Exception
 */
function locate_wordpress() {
    $env = getenv( 'WP_DIR' );
    if ( $env && is_dir( $env ) ) {
        // Got it from environment:
        return $env;
    }

	// Locate downwards.
	$directory     = new RecursiveDirectoryIterator( getcwd() );
	$iterator      = new RecursiveIteratorIterator(
		$directory,
		RecursiveIteratorIterator::LEAVES_ONLY,
		RecursiveIteratorIterator::CATCH_GET_CHILD
	);
	$regex         = new RegexIterator( $iterator, '/.*\/wp-load\.php$/', RecursiveRegexIterator::GET_MATCH );

	foreach ( $regex as $wpLoadPath ) {
        return dirname( current( $wpLoadPath ) );
	}

	throw new \RuntimeException( 'Could not find wp-load.php automatically.' );
}
