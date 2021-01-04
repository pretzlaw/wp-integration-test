<?php

namespace Pretzlaw\WPInt\Test\Filter\Assertions\AssertFilterCallbacks;


use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Test\TestCase;
use Pretzlaw\WPInt\Tests\AllTraits;
use Pretzlaw\WPInt\WPAssert;

/**
 * Class FilterDoesNotExistTest
 *
 * @package Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks
 * @backupGlobals enabled
 */
class FilterDoesNotExistTest extends TestCase
{
	protected function compatSetUp()
	{
	 parent::compatSetUp();

	 global $wp_filter;
	 $wp_filter = [];
	}

	public function testFilterNotHasCallbackSucceeds()
	{
	 static::assertNull(AllTraits::assertFilterNotHasCallback(uniqid('', true), new IsAnything()));
	}

	public function testFilterHasCallbackFails()
	{
	 $this->expectException(AssertionFailedError::class);

	 WPAssert::assertFilterHasCallback(uniqid('', true), new IsAnything());
	}

	public function testFilterNotEmptyFails()
	{
	 $this->expectException(ExpectationFailedException::class);
	 $this->expectExceptionMessage("Failed asserting that 'foo' does not have callbacks registered.");
	 WPAssert::assertFilterNotEmpty('foo');
	}

	/**
	 * FilterEmpty
	 *
	 * In case that there is no such filter given,
	 * then assertions like `assertFilterEmpty` will succeed.
	 */
	public function testFilterEmptySucceeds()
	{
	 AllTraits::assertFilterEmpty('foo');
	}
}
