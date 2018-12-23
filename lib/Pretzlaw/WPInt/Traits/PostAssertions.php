<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;

trait PostAssertions {
	use PostQueryAssertions;

	protected function expectWpPostCreationWithSubset( $expectedSubset ) {
        $mockObject = new ExpectWpInsertPost($expectedSubset);

        $mockObject->expects($this->atLeastOnce());
        $this->registerMockObject($mockObject);

        $mockObject->addFilter();
	}
}
