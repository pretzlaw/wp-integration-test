<?php


namespace Pretzlaw\WPInt\Traits;


trait PostQueryAssertions {
	protected static function mockGetPost( $id, $returnVal ) {
		if ( \is_array( $returnVal ) ) {
			$returnVal = new \WP_Post( new \ArrayObject( $returnVal ) );
		}

		wp_cache_set( $id, $returnVal, 'posts' );
	}
}
