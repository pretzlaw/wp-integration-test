<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * LoadMockedPostTest.php
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

namespace Pretzlaw\WPInt\Test\Post\GetPost;

use Pretzlaw\WPInt\Test\Post\CreatePostTestCase;
use WP_Post;

/**
 * LoadMockedPostTest
 *
 * @covers \Pretzlaw\WPInt\Mocks\ExpectationFacade
 */
class LoadMockedPostTest extends CreatePostTestCase
{
	private $postStub;
	private $postData;
	private $postId;
	private $postTitle;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->postId = random_int(1337, 9001);

		$this->postTitle = uniqid('', true);
		$this->postData = [
			'post_type' => 'page',
			'post_title' => $this->postTitle,
		];

		$this->postStub = new WP_Post((object) $this->postData);

		$this->mockGetPost($this->postId)->andReturn($this->postStub);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Mocks\Facade\ReturnMethods
	 * @covers \Pretzlaw\WPInt\Traits\PostAssertions::mockGetPost()
	 */
	public function testMockedPostWillBeReturned()
	{
		$this->mockGetPost($this->postId)->andReturn($this->postStub);

		$result = get_post($this->postId);
		static::assertEquals($this->postTitle, $result->post_title);
	}
}
