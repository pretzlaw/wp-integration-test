<?php

declare(strict_types=1);

namespace Pretzlaw\WPInt\Traits;

use Pretzlaw\WPInt\Mocks\Users\CurrentUserMock;

trait UserAssertions {
    /**
     * @param \WP_User|null $mockedUser
     */
    protected function mockCurrentUser($mockedUser)
    {
        $this->wpIntApply(
			new CurrentUserMock($GLOBALS['current_user'], $mockedUser)
		);
	}
}
