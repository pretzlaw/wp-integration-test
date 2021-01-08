<?php


namespace Pretzlaw\WPInt\Mocks\Post;


use Mockery\Matcher\Any;
use Pretzlaw\WPInt\Mocks\Filter;

class ExpectWpInsertPost extends Filter {
	/**
	 * @var array
	 */
	private $subset;

	/**
	 * ExpectWpInsertPost constructor.
	 *
	 */
	public function __construct( array $subset ) {
		parent::__construct('wp_insert_post_empty_content');

		$this->subset = $subset;
	}

	public function apply()
	{
		parent::apply()
			->atLeast()
			->times(1)
			->with(new Any(), new \Mockery\Matcher\Subset($this->subset))
			->andReturnArg(0);
	}
}
