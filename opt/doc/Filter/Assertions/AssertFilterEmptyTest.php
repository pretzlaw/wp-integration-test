<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AssertFilterEmptyTest.php
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

namespace Pretzlaw\WPInt\Test\Filter\Assertions;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Constraint\FilterEmpty;
use Pretzlaw\WPInt\Test\TestCase;
use WP_Hook;

/**
 * AssertFilterEmptyTest
 *
 * @covers \Pretzlaw\WPInt\Constraint\FilterEmpty
 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::assertFilterEmpty
 * @covers \Pretzlaw\WPInt\Filter\FilterAssertions::assertFilterNotEmpty
 * @covers \Pretzlaw\WPInt\Traits\ActionAssertions::assertActionEmpty
 * @covers \Pretzlaw\WPInt\Traits\ActionAssertions::assertActionNotEmpty
 */
class AssertFilterEmptyTest extends TestCase
{
	private $emptyFilterName = '_i_am_an_empty_filter';
	private $nonEmptyFilterName = '_i_have_hooks';

	protected function compatSetUp()
	{
		global $wp_filter;

		$wp_filter[$this->emptyFilterName] = [];
		$wp_filter[$this->nonEmptyFilterName] = [];

		if (class_exists(WP_Hook::class)) {
			$wp_filter[$this->nonEmptyFilterName] = WP_Hook::build_preinitialized_hooks(
				[
					$this->nonEmptyFilterName => $wp_filter[$this->nonEmptyFilterName],
				]
			)[$this->nonEmptyFilterName];
		}

		add_filter($this->nonEmptyFilterName, '__return_false');
	}

	public function compatTearDown()
	{
		global $wp_filter;

		unset($wp_filter[$this->emptyFilterName]);
		unset($wp_filter[$this->nonEmptyFilterName]);

		parent::compatTearDown();
	}

	public function testFilterIsEmpty()
	{
		$assertion = new FilterEmpty();
		static::assertTrue($assertion->evaluate($this->emptyFilterName, '', true));

		static::assertFilterEmpty($this->emptyFilterName);
		$this->assertActionEmpty($this->emptyFilterName);

		$this->expectException(AssertionFailedError::class);
		static::assertFilterNotEmpty($this->emptyFilterName);
	}

	public function testFilterIsNotEmpty()
	{
		global $wp_filter;

		$assertion = new FilterEmpty($wp_filter);
		static::assertFalse($assertion->evaluate($this->nonEmptyFilterName, '', true));

		static::assertFilterNotEmpty($this->nonEmptyFilterName);
		self::assertActionNotEmpty($this->nonEmptyFilterName);

		$this->expectException(ExpectationFailedException::class);
		static::assertFilterEmpty($this->nonEmptyFilterName);
	}
}
