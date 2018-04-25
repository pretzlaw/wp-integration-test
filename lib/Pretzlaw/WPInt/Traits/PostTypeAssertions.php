<?php


namespace Pretzlaw\WPInt\Traits;


trait PostTypeAssertions {
	protected static function assertPostTypeArgs( $postType, $exptectedArgs ) {
		if ( isset( $exptectedArgs['supports'] ) ) {
			// Supports is stored a bit different like `[$feature => true]` which we fix here.
			$exptectedArgs['supports'] = array_fill_keys( $exptectedArgs['supports'], true );
		}

		static::assertArraySubset( $exptectedArgs, (array) static::getPostTypeObject( $postType ) );
	}

	protected static function assertPostTypeLabels( $postType, $expectedLabels ) {
		$postTypeObject = static::getPostTypeObject( $postType );
		$labels         = (array) get_post_type_labels( $postTypeObject );

		static::assertArraySubset( $expectedLabels, $labels );
	}

	protected static function assertPostTypeRegistered( $postType ) {
		static::assertNotEmpty(
			get_post_types( [ 'name' => $postType ] ),
			sprintf( 'Post-Type "%s" has not been registered.', $postType )
		);
	}

	private static function getPostTypeObject( $postType ) {
		$postTypeObject = get_post_type_object( $postType );

		if ( false === $postTypeObject instanceof \WP_Post_Type ) {
			throw new \RuntimeException( 'Post type has no object - maybe not registered yet or typo?' );
		}

		// Sometimes the supports property gets dropped so we fetch it again.
		$postTypeObject->supports = get_all_post_type_supports( $postType );

		return $postTypeObject;
	}
}
