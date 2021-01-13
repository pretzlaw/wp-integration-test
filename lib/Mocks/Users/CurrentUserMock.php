<?php

declare(strict_types=1);

namespace Pretzlaw\WPInt\Mocks\Users;

use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;
use WP_User;

/**
 * @method InvocationMocker method($constraint)
 */
class CurrentUserMock implements ApplicableInterface, CleanUpInterface
{
	/**
	 * @var WP_User
	 */
	private $backup;
	/**
	 * @var WP_User|null
	 */
	private $currentUser;

	/**
	 * @var WP_User|null
	 */
	private $mockedUser;

	private $mock;

	/**
	 * CurrentUserMock constructor.
	 *
	 * @param WP_User $currentUser
	 * @param WP_User|null|string $mockedUser
	 */
	public function __construct(&$currentUser, $mockedUser = null)
	{
		$this->currentUser =& $currentUser;
		$this->mockedUser = $mockedUser;
		$this->backup = $currentUser;
	}

	public function apply()
	{
		$this->currentUser = $this->mockedUser;
	}

	public function __invoke()
	{
		$this->currentUser = $this->backup;
	}
}
