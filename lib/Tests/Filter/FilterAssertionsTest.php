<?php

namespace Pretzlaw\WPInt\Tests\Filter;

use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Tests\AllTraits;
use Pretzlaw\WPInt\Traits\FilterAssertions;

/**
 * Filter
 *
 * @package Pretzlaw\WPInt\Tests\Filter
 */
class FilterAssertionsTest extends AbstractTestCase {
	use FilterAssertions;

	/**
	 * Mocking filter
	 *
	 * WordPress has plenty of filters in which we want to hook during development
	 * and see if the correct data is passing through for example.
	 * Unfortunately our hook would stay in the during the whole runtime.
	 * So we introduce some filter mocks that tear down themselves after each test.
     *
     * @backupGlobals enabled
	 */
	public function testMockFilter() {
        static::assertInstanceOf( ExpectedFilter::class, $this->mockFilter( 'some' ) );
	}
}
