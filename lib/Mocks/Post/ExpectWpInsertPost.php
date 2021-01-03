<?php


namespace Pretzlaw\WPInt\Mocks\Post;


use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\MockObject\Matcher\AnyParameters;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;

class ExpectWpInsertPost extends ExpectedFilter {
    /**
     * ExpectWpInsertPost constructor.
     *
     * @param array $post_data
     */
	public function __construct( array $post_data ) {
		parent::__construct(
			'wp_insert_post_empty_content',
			true, // Aborts real insertion
			[ new AnyParameters(), new ArraySubset( $post_data ) ]
		);
	}

    protected function fixExceptionMessage(\Exception $e)
    {
        return 'wp_insert_post has not been called.';
    }
}