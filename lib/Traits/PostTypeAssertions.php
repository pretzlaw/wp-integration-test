<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Constraint\Post\Type\IsRegistered;
use WP_Post_Type;

trait PostTypeAssertions {
	/**
	 * @param string $postType
	 *
	 * @param array|WP_Post_Type $exptectedArgs
	 */
    protected static function assertPostTypeArgs(string $postType, $exptectedArgs)
    {
		static::assertArraySubset( (array) $exptectedArgs, (array) self::getPostTypeObject( $postType ) );
	}

	protected static function assertPostTypeLabels( $postType, $expectedLabels ) {
        static::assertArraySubset(
            $expectedLabels,
            (array)get_post_type_labels(
                self::getPostTypeObject($postType)
            )
        );
	}

	protected static function assertPostTypeRegistered( $postType, string $message = '' ) {
    	static::assertThat($postType, new IsRegistered(self::getAllPostTypes()), $message);
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

	private static function getAllPostTypes(): array {
    	global $wp_post_types;

    	return (array) $wp_post_types;
	}
}
