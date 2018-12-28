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
 * https://mike-pretzlaw.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@mike-pretzlaw.de so we can mail you a copy.
 *
 * @package    pretzlaw/wp-integration-test
 * @copyright  2018 Mike Pretzlaw
 * @license    https://mike-pretzlaw.de/license-generic.txt
 * @link       https://project.mike-pretzlaw.de/pretzlaw/wp-integration-test
 * @since      2018-12-27
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Posts;

use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\PostTypeAssertions;

/**
 * Post-Types
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-27
 */
class PostTypeAssertionsTest extends AbstractTestCase
{
    use PostTypeAssertions;

    public function testThrowsExceptionOnInvalidPostTypes()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Post type has no object - maybe not registered yet or typo?');

        static::assertPostTypeLabels(uniqid('', true), []);
    }
}