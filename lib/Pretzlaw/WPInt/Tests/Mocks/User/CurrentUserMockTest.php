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

namespace Pretzlaw\WPInt\Tests\Mocks\User;

use Pretzlaw\WPInt\Mocks\Users\CurrentUserMock;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\UserAssertions;

/**
 * Mock the current user
 *
 * The `wp_get_current_user` function returns the currently logged in user.
 * To have one specific user for one test this can be mocked:
 *
 *      $some_user = new \WP_User( (object) [
 *          'ID'         => 42,
 *          'user_login' => 'foo',
 *      ]);
 *
 *      $this->mockCurrentUser( $some_user );
 *
 * Now functions like `wp_get_current_user`
 * or `get_current_user_id` will return the mocked data.
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-23
 */
class CurrentUserMockTest extends AbstractTestCase
{
    use UserAssertions;

    private $mockedUser;

    protected function setUp()
    {
        parent::setUp();
        $this->mockedUser = new \WP_User(
            (object)[
                'ID' => 42,
                'user_login' => 'curb.your.ent'
            ]
        );
    }

    public function testWpReturnsMockedUser()
    {
        static::assertNotEquals(42, get_current_user_id());
        static::assertNotEquals('curb.your.ent', get_current_user());
        static::assertNotSame(wp_get_current_user(), $this->mockedUser);

        $this->mockCurrentUser($this->mockedUser);

        static::assertEquals(42, get_current_user_id());
        static::assertEquals('curb.your.ent', wp_get_current_user()->user_login);
        static::assertSame(wp_get_current_user(), $this->mockedUser);
    }

    public function testResetsCurrentUserAfterTest()
    {
        global $current_user;

        $prev = $current_user;

        static::assertNotSame($this->mockedUser, $current_user);

        $userMock = new CurrentUserMock($this->mockedUser);
        $userMock->apply();

        static::assertNotSame($prev, $current_user);
        static::assertSame($this->mockedUser, $current_user);

        $userMock->__phpunit_verify();

        static::assertSame($prev, $current_user);
        static::assertNotSame($this->mockedUser, $current_user);
    }
}