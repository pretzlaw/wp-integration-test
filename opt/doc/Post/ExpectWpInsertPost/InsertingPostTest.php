<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ${SHORT}
 *
 * LICENSE: This source file is created by the company around Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package   pretzlaw/wp-integration-test
 * @copyright 2021 Pretlaw
 * @license   https://rmp-up.de/license-generic.txt
 * @link      https://project.rmp-up.de/pretzlaw/wp-integration-test
 */

namespace Pretzlaw\WPInt\Test\Post\ExpectWpInsertPost;

use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;
use Pretzlaw\WPInt\Test\TestCase;

/**
 * InsertingPostTest
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @covers \Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost
 * @covers \Pretzlaw\WPInt\Traits\PostAssertions::expectWpPostCreationWithSubset
 */
class InsertingPostTest extends TestCase
{
	/**
	 * @group integration
	 */
	public function testNoDataIsWrittenToDatabase()
	{
		$this->markTestSkipped('Not yet migrated to mockery');
	 $pageTitle = uniqid(__METHOD__, true);

	 static::assertNull(get_page_by_title($pageTitle));

	 $expectedSubset = [
	  'post_title' => $pageTitle,
	  'post_type' => 'page',
	 ];

	 $this->expectWpPostCreationWithSubset($expectedSubset);

	 wp_insert_post($expectedSubset);

	 static::assertNull(get_page_by_title($pageTitle));
	}

	public function testFailsIfFilterDidNotRun()
	{
		$this->markTestSkipped('Not yet migrated to mockery');

		$mock = new ExpectWpInsertPost([]);

		$this->expectException(ExpectationFailedException::class);
		$this->expectExceptionMessage('wp_insert_post has not been called.');

		$mock->expects($this->once());
		$mock->addFilter();

		$mock->__phpunit_verify();
	}

	/**
	 * @testdox Registers filter on wp_insert_post_empty_content
	 */
	public function testRegistersFilter()
	{
		$this->markTestSkipped('Not yet migrated to mockery');
	 self::assertFilterNotHasCallback(
	  'wp_insert_post_empty_content',
	  new IsInstanceOf(ExpectWpInsertPost::class)
	 );

	 $this->expectWpPostCreationWithSubset([]);

	 self::assertFilterHasCallback(
	  'wp_insert_post_empty_content',
	  new IsInstanceOf(ExpectWpInsertPost::class)
	 );

	 // To suppress exception.
	 wp_insert_post([]);
	}
}
