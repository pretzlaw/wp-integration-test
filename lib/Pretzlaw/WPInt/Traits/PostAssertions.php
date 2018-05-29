<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;

trait PostAssertions {
	use PostQueryAssertions;

	protected function expectWpPostCreationWithSubset( $expectedSubset ) {
		$expectation = new ExpectWpInsertPost( $this, $expectedSubset );
		$expectation->addFilter();
	}
}
