<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains tests for MockMetaData
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
 * @since      2018-12-25
 */

namespace Pretzlaw\WPInt\Tests\Mocks;

use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\MetaDataAssertions;
use Prophecy\Argument\Token\AnyValueToken;

/**
 * Meta-Data
 *
 * Mocking meta data is as simple as the others:
 *
 *      $this->mockMetaData( 'post', 'the key', 'some value' );
 *
 * Now when you call for `get_post_meta( $some_id )`
 * it will always be returned the mocked value.
 * You can even check how often this happens
 * or have even complex return values:
 *
 *      $this->mockMetaData( 'post', 'the key' )
 *           ->expects($this->once())
 *           ->willReturn(function () {
 *              // return ...;
 *           });
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-25
 */
class MockMetaDataTest extends AbstractTestCase
{
    use MetaDataAssertions;

    /**
     * Meta-Data
     *
     * In general you can mock any kind of metadata with this even for
     * non-registered post-types:
     *
     *      $this->mockMetaData('some-cpt-slug', 'foo', 'bar');
     *
     */
    public function testItMocksAnyMetaData()
    {
        $this->mockMetaData('some-cpt-slug', 'foo', 'bar');
        static::assertEquals('bar', get_metadata('some-cpt-slug', 1337, 'foo', true));
    }

    /**
     * Meta-Data
     *
     * As long as you do not provide an object id (e.g. post id) it will work
     * for every object:
     *
     *      // All those return "bar" now no matter what the ID is
     *      get_metadata( 'some-cpt-slug' , 42, 'foo' );
     *      get_metadata( 'some-cpt-slug' , 1337, 'foo' );
     *      get_metadata( 'some-cpt-slug' , 13, 'foo' );
     */
    public function testItMocksMetaDataForAllEntities()
    {
        $this->mockMetaData('some-cpt-slug', 'foo', 'bar');

        static::assertEquals('bar', get_metadata('some-cpt-slug', 42, 'foo'));
        static::assertEquals('bar', get_metadata('some-cpt-slug', 1337, 'foo'));
        static::assertEquals('bar', get_metadata('some-cpt-slug', 13, 'foo'));
        static::assertNotEquals('bar', get_metadata('some-cpt-slug', 13, 'baz'));
    }

    /**
     * Meta-Data
     *
     * But if you want to have the meta data of one specific entry changed
     * then add the object id:
     *
     *      $this->mockMetaData('some-cpt-slug', 'foo', 'bar', 1337);
     */
    public function testMocksDataForOneEntityId()
    {
        $this->mockMetaData('some-cpt-slug', 'foo', 'bar', 1337);

        static::assertNotEquals('bar', get_metadata('some-cpt-slug', 42, 'foo', true));
        static::assertEquals('bar', get_metadata('some-cpt-slug', 1337, 'foo', true));
        static::assertNotEquals('bar', get_metadata('some-cpt-slug', 13, 'foo', true));
    }

    /**
     * Meta-Data
     *
     * Keep in mind that it makes a difference if you explicitly define the (single-)mode
     * with the 5th argument:
     *
     *      $this->mockMetaData( 'post', 'foo', 'bar', 13, true );
     *      $this->mockMetaData( 'post', 'foo', 'baz', 13, false );
     *      $this->mockMetaData( 'post', 'foo', 'qux', 42, true );
     *
     * Thus will have different results:
     *
     *      get_post_meta( 13, 'foo', true ); // "bar"
     *      get_post_meta( 13, 'foo', false ); // ["baz"] (as array!)
     *
     *      get_post_meta( 42, 'foo', true ); // "qux"
     *      get_post_meta( 42, 'foo', false ); // null
     *
     * Last one returns NULL
     * because the mock only defines what shall happen in single-mode
     * but not in the common case (`$single = false`).
     *
     */
    public function testDifferencesBetweenSingleAndGeneralMode()
    {

        static::assertNotEquals('baz', get_metadata('some-cpt-slug', 13, 'bar', true));
        static::assertNotEquals(['qux'], get_metadata('some-cpt-slug', 13, 'bar', false));

        $this->mockMetaData('some-cpt-slug', 'bar', 'baz', 13, true);
        $this->mockMetaData('some-cpt-slug', 'bar', 'qux', 13, false);

        static::assertEquals('baz', get_metadata('some-cpt-slug', 13, 'bar', true));
        static::assertEquals(['qux'], get_metadata('some-cpt-slug', 13, 'bar', false));
    }

    /**
     * Meta-Data
     *
     * You can shorten the above examples for some common post-types:
     *
     *      $this->mockPostMeta('meta-key', 'some value');
     *      $this->mockUserMeta('last_login', time());
     *
     * @dataProvider getInternalPostTypes
     */
    public function testShortcutForInternalPostTypes($postType, $postTypeMethod)
    {
        $this->$postTypeMethod('foo', 'bar', 1337);

        static::assertNotEquals('bar', get_metadata($postType, 42, 'foo', true));
        static::assertEquals('bar', get_metadata($postType, 1337, 'foo', true));
        static::assertNotEquals('bar', get_metadata($postType, 13, 'foo', true));
    }

    public function getInternalPostTypes()
    {
        return [
            ['post', 'mockPostMeta'],
            ['user', 'mockUserMeta'],
        ];
    }
}
