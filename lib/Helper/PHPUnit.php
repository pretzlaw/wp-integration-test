<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PHPUnit.php
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

namespace Pretzlaw\WPInt\Helper;

/**
 * PHPUnit
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class PHPUnit
{
	private static $version;

	/**
	 * List of classes last seen in the mapped PHPUnit version
	 *
	 * Those classes are all deprecated.
	 * Their last occurrence tells us something about the PHPUnit version.
	 *
	 * @var int[]
	 */
	private static $classToVersion = [
		\PHPUnit\Framework\BaseTestListener::class => 6,
		\PHPUnit\Util\TestDox\TestResult::class => 7,
		\PHPUnit\Util\Configuration::class => 8,
		\PHPUnit\Util\Blacklist::class => 9
	];

	public static function getVersion(): int
	{
		if (null === self::$version) {
			foreach (self::$classToVersion as $className => $phpUnitVersion) {
				if (class_exists($className)) {
					self::$version = $phpUnitVersion;
					break;
				}
			}

			if (null === self::$version) {
				throw new \DomainException('Unsupported PHPUnit version');
			}
		}

		return self::$version;
	}
}
