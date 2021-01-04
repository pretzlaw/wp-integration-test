<?php

$directory = new RecursiveDirectoryIterator( __DIR__ );
$iterator  = new RecursiveIteratorIterator( $directory );
$regex     = new RegexIterator( $iterator, '/.*\/functions\.php$/', RecursiveRegexIterator::GET_MATCH );

foreach ( $regex as $functionFile ) {
	if ( is_array( $functionFile ) ) {
        $functionFile = current( $functionFile );
	}

	$functionFile = realpath( $functionFile );

	if ( 0 !== strpos( $functionFile, __DIR__ ) ) {
        // Seems like we followed some symlink and we don't trust it.
        continue;
	}

	require_once $functionFile;
}
