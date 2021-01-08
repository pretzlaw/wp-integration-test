<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PostCreationWasNotTriggeredTest.php
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

namespace Pretzlaw\WPInt\Test\Post\CreatePost;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Test\Post\CreatePostTestCase;

/**
 * PostCreationWasNotTriggeredTest
 */
class PostCreationWasNotTriggeredTest extends CreatePostTestCase
{
	/**
	 * @covers \Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost
	 * @group integration
	 */
	public function testMissingInsertionIsNoticedByMockClass()
	{
		$this->wpIntApply($this->expectWpInsertPost);

		$this->expectException(AssertionFailedError::class);
		$this->expectWpInsertPost->verifyPostCondition();
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\PostAssertions::expectWpInsertPost()
	 * @group acceptance
	 */
	public function testExpectationShouldFail()
	{
		$this->expectException(AssertionFailedError::class);
		$this->expectWpInsertPost($this->wpInsertPostData);

		$this->wpIntPostConditions();
	}
}
