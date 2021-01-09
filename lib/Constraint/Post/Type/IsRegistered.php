<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * IsRegistered.php
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

namespace Pretzlaw\WPInt\Constraint\Post\Type;

use Pretzlaw\WPInt\Constraint\Constraint;

/**
 * IsRegistered
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class IsRegistered extends Constraint
{
	/**
	 * @var array
	 */
	private $postTypes;

	public function __construct(array $postTypes)
	{
		parent::__construct();

		$this->postTypes = $postTypes;
	}

	protected function matches($postTypeName): bool
	{
		return is_scalar($postTypeName)
			&& false === empty($this->postTypes[$postTypeName])
			&& $postTypeName === $this->postTypes[$postTypeName]->name;
	}


	public function toString(): string
	{
		return 'is a registered post-type';
	}
}
