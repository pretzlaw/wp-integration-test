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

use Pretzlaw\WPInt\Traits\WordPressTests;
use ReflectionMethod;
use ReflectionObject;

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
	 */
	public function testCoverageIsDefined()
	{
		// Does class have "@covers" comment?
		$docComment = (new ReflectionObject($this))->getDocComment();

		if ($this->hasCoversTag($docComment)) {
			static::assertTrue($this->hasCoversTag($docComment));

			return;
		}

		$className = get_class($this);
		foreach (get_class_methods($className) as $methodName) {
			/** @noinspection NotOptimalIfConditionsInspection */
			if (
				0 !== strpos($methodName, 'test')
				|| 'testCoverageIsDefined' === $methodName
			) {
				continue;
			}

			$method = new ReflectionMethod($this, $methodName);

			if (false === $method->isPublic()) {
				continue;
			}

			static::assertTrue(
				$this->hasCoversTag((string) $method->getDocComment()),
				sprintf(
					'Please specify @covers for "%s" or "%s".',
					$className,
					$methodName
				)
			);
		}
	}

	private function hasCoversTag($docComment): bool
	{
		return (bool) preg_match('/\n\s+\\*\s+@covers\s+[\\\]?\w/mu', $docComment);
	}

	/**
	 * Asserting that the callback has an exception of given type
	 *
	 * @param string $expectedClass
	 * @param $callback
	 */
	protected function assertException(string $expectedClass, $callback)
	{
		try {
			$callback();
		} catch (\Exception $e) {
			static::assertInstanceOf($expectedClass, $e);

			return;
		}

		self::fail('No exception has been thrown');
	}
}
