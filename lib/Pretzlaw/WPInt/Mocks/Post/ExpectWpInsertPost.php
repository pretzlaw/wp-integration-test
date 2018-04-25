<?php


namespace Pretzlaw\WPInt\Mocks\Post;


use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\MockObject\Matcher\AnyParameters;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;

class ExpectWpInsertPost extends ExpectedFilter {
	/**
	 * @var string
	 */
	private $type;

	public function __construct( $testCase, $post_data ) {
		parent::__construct(
			$testCase,
			'wp_insert_post_empty_content',
			true, // Aborts real insertion
			[ new AnyParameters(), new ArraySubset( $post_data ) ]
		);
	}

	public function getErrorMessage() {
		return 'wp_insert_post has not been called.';
	}
}
