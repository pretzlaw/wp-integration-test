<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * CreatingPostTest.php
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

use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;
use Pretzlaw\WPInt\Test\Post\CreatePostTestCase;
use WP_Error;

/**
 * CreatingPostTest
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class CreatingPostTest extends CreatePostTestCase
{
	/**
	 * @covers \Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost
	 * @group unit
	 */
	public function testChecksPostCreationWithSpecificSubset()
	{
		$this->expectWpInsertPost->apply();

		$result = wp_insert_post($this->wpInsertPostData);

		if ($result instanceof WP_Error) {
			// Just to see what the error message is in test-output.
			$result = $result->get_error_message();
		}

		static::assertNotInstanceOf(WP_Error::class, $result, $result);

		// 1 assertion should've been made (and succeeded)
		static::assertSame(1, $this->expectWpInsertPost->verifyPostCondition());
	}
}
