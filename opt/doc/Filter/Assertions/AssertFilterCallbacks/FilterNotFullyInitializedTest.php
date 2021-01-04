<?php

namespace Pretzlaw\WPInt\Test\Filter\Assertions\AssertFilterCallbacks;


use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Test\TestCase;
use Pretzlaw\WPInt\Tests\AllTraits;
use Pretzlaw\WPInt\WPAssert;

/**
 * Class FilterNotFullyInitializedTest
 *
 * @package Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks
 * @backupGlobals enabled
 * @inheritdoc
 */
class FilterNotFullyInitializedTest extends TestCase
{
	/**
	 * @var string
	 */
	private $targetFilter;

	protected function compatSetUp()
	{
	 parent::compatSetUp();

	 global $wp_filter;

	 $this->targetFilter = uniqid('', true);
	 $wp_filter[$this->targetFilter] = null;
	}

	/**
	 * AssertFilterNotHasCallback
	 *
	 * It could happen that there is no such filter present.
	 * In that case the assertion for not having a callback would pass too.
	 */
	public function testAssertFilterNotHasCallback()
	{
	 static::assertNull(AllTraits::assertFilterNotHasCallback($this->targetFilter, ''));
	}

	/**
	 * FilterEmpty
	 *
	 * The filter could also exist but contain nothing,
	 * neither a \WP_Hook object nor an array but it could be null.
	 * In that case the filter is considered to be empty too.
	 */
	public function testFilterEmptySucceeds()
	{
	 static::assertNull(WPAssert::assertFilterEmpty($this->targetFilter));
	}

	public function testFilterNotEmptyFails()
	{
	 $this->expectException(AssertionFailedError::class);
	 WPAssert::assertFilterNotEmpty($this->targetFilter);
	}

	public function testFitlerNotEmptySucceeds()
	{
	 WPAssert::assertFilterNotEmpty('init');
	}
}
