<?php


namespace Pretzlaw\WPInt\Traits;


use Mockery\Matcher\AndAnyOtherArgs;
use PHPUnit\Framework\MockObject\MockObject;
use Pretzlaw\WPInt\Mocks\ExpectationFacade;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\Facade\ReturnMethods;
use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;
use Pretzlaw\WPInt\Mocks\ReturnOnly;

trait PostAssertions
{
	/**
	 * @param int $id Post-ID.
	 * @param \WP_Post|array $returnVal Post object or it's data as array (will be transformed into post object).
	 *
	 * @return ReturnMethods|ExpectationFacade
	 */
	protected function mockGetPost(int $id)
	{
		$expectation = $this->mockCache()
				->shouldReceive('get')
				->with($id, 'posts', new AndAnyOtherArgs());

		return new class ($expectation) extends ExpectationFacade {
			use ReturnMethods;
		};
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
