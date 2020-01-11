<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Tests for PostTypeAssertions
 *
 * LICENSE: This source file is created by the company around Mike Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package    pretzlaw/wp-integration-test
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @link       https://project.rmp-up.de/pretzlaw/wp-integration-test
 * @since      2018-12-27
 */

namespace Pretzlaw\WPInt\Tests\Posts\PostTypeAssertions;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\PostTypeAssertions;

/**
 * Post-Types
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-27
 */
class AssertPostTypeArgsTest extends AbstractTestCase
{
    use PostTypeAssertions;

    public function testFailsOnWrongSupportPart()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertPostTypeArgs('post', [
            'post_type' => 'post',
            'is_internal' => true,
            'supports' => [
                'database_normalization' => true,
            ]
        ]);
    }

    public function testSucceedsWithCorrectData()
    {
        self::assertPostTypeArgs('post', [
            'supports' => [
                'title' => true,
                'editor' => true,
                'author' => true,
                'thumbnail' => true,
                'excerpt' => true,
                'trackbacks' => true,
                'comments' => true,
                'revisions' => true,
                'post-formats' => true,
            ]
        ]);

    }
}