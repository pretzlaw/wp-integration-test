<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Test case for ExpectedMetaUpdate
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
 * @since      2018-12-24
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\MetaData\ExpectedMetaUpdateTest;

use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Mocks\ExpectedMetaUpdate;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\MetaDataAssertions;

/**
 * Watch meta-data update by value
 *
 * You can assert that a filter has run but also check for specific values.
 * Let's assume you have such code:
 *
 *      function foo_enable( $id ) {
 *          update_post_meta( $id, 'foo_enabled', 'yes' );
 *      }
 *
 * Then the test for that could be:
 *
 *      $this->expectUpdatePostMeta( 'foo_enabled', 'yes' );
 *      foo_enable( 42 );
 *
 * The test succeeds when "foo_enabled" will set to "yes" at some point.
 * But it would fail if there is no such assignment during runtime.
 *
 * @copyright 2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since     2018-12-24
 */
class WatchSpecificValuesTest extends AbstractTestCase
{
    use MetaDataAssertions;

    /**
     * @var string
     */
    private $metaKey;
    /**
     * @var string
     */
    private $metaValue;

    protected function setUp()
    {
        parent::setUp();

        $this->metaKey = uniqid('', true);
        $this->metaValue = uniqid('', true);
    }

    public function testFailsWhenMetaValueHasNotBeenSet()
    {
        $this->expectException(ExpectationFailedException::class);

        $expected = new ExpectedMetaUpdate('post', $this->metaKey, $this->metaValue);
        $expected->expects($this->once());
        $expected->addFilter();

        $expected->__phpunit_verify();
    }

    public function testSucceedsWhenMetaValueHasBeenSet()
    {
        $this->expectUpdateMeta('post', $this->metaKey, $this->metaValue);

        update_post_meta(1337,$this->metaKey, $this->metaValue);
    }

    public function testSucceedsWhenOtherValuesAreInBetween()
    {
        $this->expectUpdateMeta('post', $this->metaKey, $this->metaValue);

        update_post_meta(1337,$this->metaKey, uniqid('', true));
        update_post_meta(1337,$this->metaKey, uniqid('', true));
        update_post_meta(1337,$this->metaKey, uniqid('', true));
        update_post_meta(1337,$this->metaKey, $this->metaValue);
    }
}