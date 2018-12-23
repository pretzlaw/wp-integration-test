<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ${SHORT}
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
 * @copyright  2018 Mike Pretlaw
 * @license    https://mike-pretzlaw.de/license-generic.txt
 * @link       https://project.mike-pretzlaw.de/pretzlaw/wp-integration-test
 * @since      2018-12-23
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Mocks\Post\ExpectWpInsertPost;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Filter\FilterAssertions;
use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\PostAssertions;

/**
 * InsertingPostTest
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-23
 */
class InsertingPostTest extends AbstractTestCase
{
    use PostAssertions;
    use FilterAssertions;

    /**
     * @group integration
     *
     */
    public function testNoDataIsWrittenToDatabase()
    {
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