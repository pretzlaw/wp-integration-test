<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;

trait PostAssertions {
	use PostQueryAssertions;

	private $wpPostClutter = [];

	protected function expectWpPostCreationWithSubset( $expectedSubset ) {
        $mockObject = new ExpectWpInsertPost($expectedSubset);

        $mockObject->expects($this->atLeastOnce());
        $this->registerMockObject($mockObject);
        $this->wpPostClutter[] = $mockObject;

        $mockObject->addFilter();
	}

    /**
     * @after
     */
	protected function tearDownPostClutter() {
        foreach ($this->wpPostClutter as $clutter) {
            if ($clutter instanceof ExpectedFilter) {
                $clutter->removeFilter();
            }
        }
    }
}
