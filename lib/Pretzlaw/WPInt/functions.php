<?php

namespace Pretzlaw\WPInt;

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

	$_SERVER = array_merge(
		$serverVars,
		$_SERVER
	);
}

/**
 * @param $path
 *
 * @throws \Exception
 */
function load_wp( $path ) {
	$wpLoad = $path . \DIRECTORY_SEPARATOR . 'wp-blog-header.php';

	if ( ! \file_exists( $wpLoad ) ) {
		throw new \Exception( 'Missing wp-blog-header.php to load WordPress' );
	}

	require_once $wpLoad;
}

/**
 * @param null $path
 *
 * @throws \Exception
 */
function run_wp( $path = null ) {
	\Pretzlaw\WPInt\prepare_env();

	if ( null === $path ) {
		$path = locate_wordpress();
	}

	load_wp( $path );
}

/**
 * @throws \Exception
 */
function locate_wordpress() {
	// Locate downwards.
	$directory     = new \RecursiveDirectoryIterator( getcwd() );
	$iterator      = new \RecursiveIteratorIterator( $directory );
	$regex         = new \RegexIterator( $iterator, '/.*\/wp-load\.php$/', \RecursiveRegexIterator::GET_MATCH );
	$possibleFiles = \iterator_to_array( $regex );

	if ( count( $possibleFiles ) > 1 ) {
		throw new \Exception( 'Could not determine which wp-load.php should be used.' );
	}

	if ( \count( $possibleFiles ) !== 1 ) {
		throw new \Exception( 'Could not find wp-load.php automatically.' );
	}

	$match = current( $possibleFiles );

	return dirname( \current( $match ) );
}