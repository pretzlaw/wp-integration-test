<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Tests for ExpectedMetaUpdate
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
 * @copyright  2020 Mike Pretlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @link       https://project.rmp-up.de/pretzlaw/wp-integration-test
 * @since      2018-12-24
 */

namespace Pretzlaw\WPInt\Tests\MetaData;

use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Filter\FilterAssertions;
use Pretzlaw\WPInt\Mocks\ExpectedMetaUpdate;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\MetaDataAssertions;
use Pretzlaw\WPInt\Traits\WordPressTests;

/**
 * Assert update_metadata
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-24
 */
class ExpectedMetaUpdateTest extends AbstractTestCase
{
    use WordPressTests;
    /**
     * @var string
     */
    private $metaKey;

    protected function setUp()
    {
        parent::setUp();

        $this->metaKey = uniqid('', true);
    }

    public function testItRegistersItselfAsFilter()
    {
        self::assertFilterNotHasCallback('update_post_metadata', new IsInstanceOf(ExpectedMetaUpdate::class));

        $expected = new ExpectedMetaUpdate('post', '');
        $expected->addFilter();

        self::assertFilterHasCallback('update_post_metadata', new IsInstanceOf(ExpectedMetaUpdate::class));

        $expected->removeFilter();
    }

    public function testItSanitizesAfterTest()
    {
        $expected = new ExpectedMetaUpdate('post', '');
        $expected->addFilter();

        self::assertFilterHasCallback('update_post_metadata', new IsInstanceOf(ExpectedMetaUpdate::class));
        $expected->__phpunit_verify();
        self::assertFilterNotHasCallback('update_post_metadata', new IsInstanceOf(ExpectedMetaUpdate::class));
    }

    public function testItThrowsExceptionWhenMetaUpdateIsNotAsExpected()
    {
        $this->expectException(ExpectationFailedException::class);

        $expected = new ExpectedMetaUpdate('post', '');
        $expected->expects($this->once());
        $expected->addFilter();

        $expected->__phpunit_verify();
    }

    public function testItSucceedsWhenMetaUpdateOccured()
    {
        $this->expectUpdateMeta('post', $this->metaKey, '');

        update_post_meta(1337, $this->metaKey, '');
    }

    public function testItCanWatchForUserMeta()
    {
        $this->expectUpdateMeta('user', $this->metaKey, '');
        update_user_meta(1337, $this->metaKey, '');
    }

    public function testItCanWatchForCustomTypes()
    {
        global $wpdb;

        $type = md5(uniqid('', true));
        $wpdb->{$type . 'meta'} = $wpdb->postmeta;
        $this->expectUpdateMeta($type, $this->metaKey, '');

        // Suppress update_metadata from running a DB statement.
        $this->mockFilter('update_' . $type . '_metadata')
            ->expects($this->once())
            ->willReturn(true);

        update_metadata($type, 1337, $this->metaKey, '');

        unset($wpdb->{$type . 'meta'});
    }
}