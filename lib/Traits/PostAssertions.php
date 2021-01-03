<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\MockObject\MockObject;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;

trait PostAssertions {
	use PostQueryAssertions;

    /**
     * @var MockObject[]
     * @deprecated 0.4 Please use wpIntMocks instead.
     */
	private $wpPostClutter = [];

	protected function expectWpPostCreationWithSubset( $expectedSubset ) {
        $mockObject = new ExpectWpInsertPost($expectedSubset);

        $mockObject->expects($this->atLeastOnce());
        $this->wpIntMocks[] = $mockObject;
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
