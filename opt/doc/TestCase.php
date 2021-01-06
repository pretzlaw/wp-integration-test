<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * TestCase.php
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

namespace Pretzlaw\WPInt\Test;

use PHPUnit\Framework\Error\Warning;
use Pretzlaw\WPInt\Traits\WordPressTests;
use ReflectionClass;

/**
 * TestCase
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class TestCase extends \RmpUp\PHPUnitCompat\TestCase
{
	use WordPressTests;

	public function compatTearDown()
	{
		global $wpdb;

		static::assertTrue($wpdb->check_connection());
	}

	/**
	 * Assert that each test has test-coverage configured
	 *
	 * For a more stable system thanks to higher code-coverage
	 * each test has to define which part it tests.
	 * This must be done in the class comment
	 * or on test-methods.
	 *
	 * @internal
	 * @beforeClass
	 */
	public static function checkForCoverageDocComments()
	{
		// Does class have "@covers" comment?
		$reflection = new ReflectionClass(static::class);
		$docComment = $reflection->getDocComment();

		if (self::hasCoversTag($docComment)) {
			return;
		}

		$className = $reflection->getName();
		foreach (get_class_methods($className) as $methodName) {
			if (0 !== strpos($methodName, 'test')) {
				continue;
			}

			$method = $reflection->getMethod($methodName);

			if (false === $method->isPublic()) {
				continue;
			}

			if (false === self::hasCoversTag((string) $method->getDocComment())) {
				// Found one without @covers doc-comment tag.
				throw new Warning(
					sprintf('Please specify @covers for "%s" or all its methods.', $className),
					0,
					__FILE__,
					0
				);
			}
		}

		// All methods checked so all have a @covers tag
	}

	private static function hasCoversTag($docComment): bool
	{
		return (bool) preg_match('/\n\s+\\*\s+@covers\s+[\\\]?\w/mu', $docComment);
	}
}
