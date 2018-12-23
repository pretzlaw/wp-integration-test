<?php

namespace Pretzlaw\WPInt\Mocks\Users;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @method InvocationMocker method( $constraint )
 */
class CurrentUserMock implements MockObject {
    /**
     * @var \WP_User|null
     */
	private $originalUser;

    /**
     * @var \WP_User|null
     */
	private $mockedUser;

    /**
     * CurrentUserMock constructor.
     *
     * @param \WP_User|null $mockedUser
     * @param null $originalUser
     */
    public function __construct($mockedUser, $originalUser = null)
    {
        if (null === $originalUser) {
            global $current_user;
            $originalUser = $current_user;
        }

        $this->originalUser = $originalUser;
		$this->mockedUser   = $mockedUser;
	}

	public function apply() {
		global $current_user;

		$current_user = $this->mockedUser;
	}

	/**
	 * Registers a new expectation in the mock object and returns the match
	 * object which can be infused with further details.
	 *
	 * @param Invocation $matcher
	 *
	 * @return InvocationMocker
	 */
	public function expects( Invocation $matcher ) {

	}

	/**
	 * @return InvocationMocker
	 */
	public function __phpunit_setOriginalObject( $originalObject ) {

	}

	/**
	 * @return InvocationMocker
	 */
	public function __phpunit_getInvocationMocker() {

	}

	/**
	 * Verifies that the current expectation is valid. If everything is OK the
	 * code should just return, if not it must throw an exception.
	 *
	 * @throws ExpectationFailedException
	 */
	public function __phpunit_verify() {
		global $current_user;

		$current_user = $this->originalUser;
	}

	/**
	 * @return bool
	 */
	public function __phpunit_hasMatchers() {
		return false;
	}

	public function __call( $name, $arguments ) {

	}
}