<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AssertShortcodeExistsTest.php
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

namespace Pretzlaw\WPInt\Test\Shortcode\ShortcodeDoesNotExist;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Test\Shortcode\ShortcodeDoesNotExistTestCase;
use Pretzlaw\WPInt\Test\Shortcode\ShortcodeExistsTestCase;

/**
 * AssertShortcodeExistsTest
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class AssertShortcodeExistsTest extends ShortcodeDoesNotExistTestCase
{
	/**
	 * @group unit
	 *
	 * @covers \Pretzlaw\WPInt\Constraint\Shortcode\ShortcodeExists::evaluate()
	 */
	public function testConstraintIfShortcodeExistsIsFalse()
	{
		static::assertFalse(
			$this->shortcodeExistsConstraint->evaluate($this->someUndefinedShortcodeName, '', true)
		);
	}

	/**
	 * @group acceptance
	 *
	 * @covers \Pretzlaw\WPInt\Constraint\Shortcode\ShortcodeExists
	 */
	public function testAssertionIfShortcodeExistsShowsErrorMessage()
	{
		$this->expectException(AssertionFailedError::class);
		$this->expectExceptionMessageRegExp(
			sprintf(
				'/Failed asserting that ["\']%s["\'] is registered as shortcode/',
				preg_quote($this->someUndefinedShortcodeName, '/')
			)
		);

		self::assertShortcodeExists($this->someUndefinedShortcodeName);
	}

	/**
	 * @group integration
	 *
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::assertShortcodeNotExists
	 */
	public function testAssertionsIfShortcodeDoesNotExistSucceeds()
	{
		self::assertShortcodeNotExists($this->someUndefinedShortcodeName);
	}
}
