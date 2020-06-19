<?php


namespace Pretzlaw\WPInt\Traits;


trait PostQueryAssertions {
    /**
     * @param int $id Post-ID.
     * @param \WP_Post|array $returnVal Post object or it's data as array (will be transformed into post object).
     *
     * @deprecated 0.4.0 Use PostAssertions::mockGetPost instead
     */
    protected static function mockGetPost(int $id, $returnVal)
    {
		if ( \is_array( $returnVal ) ) {
			$returnVal = new \WP_Post( new \ArrayObject( $returnVal ) );
		}

        if (!$returnVal->ID) {
            $returnVal->ID = $id;
        }

		wp_cache_set( $id, $returnVal, 'posts' );
	}
}
