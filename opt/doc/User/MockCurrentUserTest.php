<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MockCurrentUserTest.php
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

namespace Pretzlaw\WPInt\Test\User;

use Pretzlaw\WPInt\Mocks\Users\CurrentUserMock;
use Pretzlaw\WPInt\Test\TestCase;
use WP_User;

/**
 * MockCurrentUserTest
 *
 * @covers \Pretzlaw\WPInt\Mocks\Users\CurrentUserMock
 */
class MockCurrentUserTest extends TestCase
{
	/**
	 * @var CurrentUserMock
	 */
	private $currentUserMock;
	private $mockedUser;
	private $currentUser;

	protected function compatSetUp()
	{
		$this->mockedUser = new WP_User();
		$this->currentUser = new WP_User(1);
		$this->currentUserMock = new CurrentUserMock($this->currentUser, $this->mockedUser);
	}

	public function testCanMockCurrentUser()
	{
		$this->wpIntApply($this->currentUserMock);

		self::assertSame($this->mockedUser, $this->currentUser);
	}

	public function testRecoversUserAfterTest()
	{
		$previous = $this->currentUser;
		$this->assertIsNotMockOf(WP_User::class, $this->currentUser);

		$this->wpIntApply($this->currentUserMock);

		self::assertSame($this->mockedUser, $this->currentUser);

		$this->wpIntCleanUp();

		self::assertNotSame($this->mockedUser, $this->currentUser);
		self::assertSame($previous, $this->currentUser);
	}

	/**
	 * @group integration
	 * @covers \Pretzlaw\WPInt\Traits\UserAssertions::mockCurrentUser()
	 */
	public function testCanMockGlobalUser()
	{
		$previousUser = wp_get_current_user();

		$mockedUser = new WP_User();
		$this->mockCurrentUser($mockedUser);

		static::assertSame($mockedUser, wp_get_current_user());
		static::assertNotSame($previousUser, wp_get_current_user());
	}
}
