<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MockCurrentPostTest.php
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
 * @copyright 2020 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Posts;

use Pretzlaw\WPInt\Mocks\Post\CurrentPost;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use WP_Post;

/**
 * Mock current post
 *
 * To temporary mock the current post for one test you can use the
 *
 * @copyright 2020 Pretzlaw (https://rmp-up.de)
 */
class MockCurrentPostTest extends AbstractTestCase
{

    /**
     * @var array
     */
    private $expected;

    protected function assertPostIsMocked()
    {
        /** @var \WP_Post $post */
        $post = get_post();

        static::assertInstanceOf(WP_Post::class, $post);
        static::assertEquals($this->expected['post_title'], $post->post_title);
        static::assertEquals($this->expected['post_content'], $post->post_content);
    }

    protected function setUp()
    {
        parent::setUp();

        $GLOBALS['post'] = null;

        $this->expected = [
            'ID' => 44,
            'post_title' => uniqid('', true),
            'post_content' => uniqid('', true),
        ];

        static::assertNull(get_post());
    }

    public function testMockCurrentPost()
    {
        $this->mockCurrentPost($this->expected);

        $this->assertPostIsMocked();
    }

    public function testMockIsGoneAfterTest()
    {
        $mock = new CurrentPost(new \WP_Post((object) $this->expected));
        $mock->register();

        $this->assertPostIsMocked(); // to check if post is there

        $mock->__phpunit_verify(true); // simulate post-test situation

        static::assertNull(get_post());
    }
}