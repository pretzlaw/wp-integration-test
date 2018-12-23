<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Mocks\Users\CurrentUserMock;

trait UserAssertions {
    /**
     * @param \WP_User|null $mockedUser
     */
    protected function mockCurrentUser($mockedUser)
    {
		global $current_user;

		$currentUserMock = new CurrentUserMock( $current_user, $mockedUser );
		$currentUserMock->apply();

		$this->registerMockObject( $currentUserMock );
	}
}