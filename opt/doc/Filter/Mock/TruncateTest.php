<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * TruncateTest.php
 *
 * LICENSE: This source file is created by the company around M. Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package   wp-integration-test
 * @copyright 2021 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Test\Filter\Mock;

use Pretzlaw\WPInt\Mocks\CannotBeAppliedMoreThanException;
use Pretzlaw\WPInt\Mocks\Filter\TruncateFilter;
use Pretzlaw\WPInt\Test\TestCase;
use Pretzlaw\WPInt\Filter\FilterAssertions;

/**
 * Truncate filter
 *
 * When you need to have one specific filter completely clean
 * without any callback,
 * then you can use ...
 *
 * ```php
 * $this->truncateFilter()
 * // or
 * $this->truncateFilter('the_content')
 * ```
 *
 * To remove all registered callbacks from this filter.
 * But don't worry the callbacks will be recovered after the test.
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 * @covers \Pretzlaw\WPInt\Mocks\Filter\TruncateFilter
 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::truncateFilter
 */
class TruncateTest extends TestCase
{
	public function testRemovesAllFilter()
	{
	 static::assertFilterNotEmpty('the_content');

	 $this->truncateFilter('the_content');

	 static::assertFilterEmpty('the_content');
	}

	/**
	 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::getWpHooksCallbacks
	 */
	public function testRecoversAllFilterAfterwards()
	{
	 static::assertFilterNotEmpty('the_content');
	 $beforeTruncate = self::getWpHooksCallbacks('the_content');

	 $this->truncateFilter();

	 static::assertFilterEmpty('the_content');
	 static::assertEmpty(self::getWpHooksCallbacks('the_content'));

	 $this->wpIntCleanUp();

	 $afterRecovery = self::getWpHooksCallbacks('the_content');
	 static::assertEquals($beforeTruncate, $afterRecovery);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::getWpHooksCallbacks
	 */
	public function testRecoverSingleFilter()
	{
	 $filterName = uniqid('', true);
	 static::assertFilterNotEmpty('the_content');

	 // begin backup
	 static::assertEmpty(
	 	self::getWpHooksCallbacks($filterName),
		'Stub filter is not empty - very strange.'
	 );

	 $this->truncateFilter($filterName);

	 static::assertFilterNotEmpty('the_content', 'the_content did not stay intact');

	 // fill filter with testing crap
	 add_filter($filterName, '__return_false');
	 static::assertFilterNotEmpty($filterName, 'Hook could not be registered');

	 // recover
	 $this->wpIntCleanUp();
	 static::assertFilterEmpty($filterName, 'Filter has not been truncated');
	}

	public function testCanBringBackDeletedFilter()
	{
	 $beforeDestroyal = static::getWpHooksCallbacks('the_content');
	 static::assertFilterNotEmpty('the_content');

	 $this->truncateFilter(['the_content']);
	 static::assertFilterEmpty('the_content');

	 // completely destroy it
	 global $wp_filter;
	 unset($wp_filter['the_content']);

	 $this->wpIntCleanUp();

	 static::assertArrayHasKey(
	  'the_content',
	  $wp_filter,
	  'the_content has not been recovered'
	 );

	 static::assertNotEmpty(static::getWpHooksCallbacks('the_content'));
	}

	public function testCanNotBeCalledMoreThanOnce()
	{
	 $x = [];
	 $t = new TruncateFilter([], $x);
	 $t->apply();

	 $this->expectException(CannotBeAppliedMoreThanException::class);
	 $t->apply();
	}

	/**
	 * WordPress hooks were arrays back in the good old times
	 *
	 * @internal
	 */
	public function testWorksWithDeprecatedWpHookDefinition()
	{
	 $filter = ['the_content' => [1,2,3]];
	 $t = new TruncateFilter(['the_content'], $filter);

	 $t->apply();

	 static::assertEmpty($filter['the_content']);

	 $t();

	 static::assertEquals([1,2,3], $filter['the_content']);
	}
}
