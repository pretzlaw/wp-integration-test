<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Mocks\Users\CurrentUserMock;

trait UserAssertions {
	protected function mockCurrentUser( \WP_User $mockedUser ) {
		global $current_user;

		$currentUserMock = new CurrentUserMock( $current_user, $mockedUser );
		$currentUserMock->apply();

		$this->registerMockObject( $currentUserMock );
	}
}