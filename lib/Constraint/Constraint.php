<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Constraint.php
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

namespace Pretzlaw\WPInt\Constraint;

use PHPUnit\Framework\Constraint\Constraint as PHPUnitConstraint;

/**
 * Constraint
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
abstract class Constraint extends PHPUnitConstraint
{
	/**
	 * Compatibility to newer PHPUnit versions
	 *
	 * The constructor has been removed in some version.
	 * We introduce an empty one here that decides when to call the parent,
	 * so that all derived classes can just call the parent without
	 * raising the error:
	 *
	 * > Error: Cannot call constructor
	 */
	public function __construct()
	{
		if (method_exists(PHPUnitConstraint::class, '__construct')) {
			parent::__construct();
		}
	}
}
