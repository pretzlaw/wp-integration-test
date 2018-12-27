<?php


namespace Pretzlaw\WPInt\Traits;


trait PostTypeAssertions {
    protected static function assertPostTypeArgs(string $postType, array $exptectedArgs)
    {
		static::assertArraySubset( $exptectedArgs, (array) static::getPostTypeObject( $postType ) );
	}

	protected static function assertPostTypeLabels( $postType, $expectedLabels ) {
        static::assertArraySubset(
            $expectedLabels,
            (array)get_post_type_labels(
                static::getPostTypeObject($postType)
            )
        );
	}

	protected static function assertPostTypeRegistered( $postType ) {
		static::assertNotEmpty(
			get_post_types( [ 'name' => $postType ] ),
			sprintf( 'Post-Type "%s" has not been registered.', $postType )
		);
	}

    private static function getPostTypeObject(string $postType)
    {
		$postTypeObject = get_post_type_object( $postType );

		if (
		    false === $postTypeObject instanceof \WP_Post_Type
            && !is_object($postTypeObject)
        ) {
			throw new \RuntimeException( 'Post type has no object - maybe not registered yet or typo?' );
		}

		// Sometimes the supports property gets dropped so we fetch it again.
		$postTypeObject->supports = get_all_post_type_supports( $postType );

		return $postTypeObject;
	}
}
