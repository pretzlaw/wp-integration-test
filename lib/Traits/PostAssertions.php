<?php


namespace Pretzlaw\WPInt\Traits;


use Mockery\Matcher\Any;
use PHPUnit\Framework\MockObject\MockObject;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;

trait PostAssertions {
	/**
	 * @param int $id Post-ID.
	 * @param \WP_Post|array $returnVal Post object or it's data as array (will be transformed into post object).
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

    /**
     * @var MockObject[]
     * @deprecated 0.4 Please use wpIntMocks instead.
     */
	private $wpPostClutter = [];

	protected function expectWpInsertPost( $expectedSubset ) {
		$this->wpIntApply(new ExpectWpInsertPost($expectedSubset));
	}
}
