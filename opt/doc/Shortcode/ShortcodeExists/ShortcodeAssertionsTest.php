<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ShortcodeAssertionsTest.php
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

namespace Pretzlaw\WPInt\Test\Shortcode\ShortcodeExists;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Constraint\Shortcode\ShortcodeExists;
use Pretzlaw\WPInt\Test\Shortcode\ShortcodeExistsTestCase;

/**
 * ShortcodeAssertionsTest
 *
 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::getAllShortcodes()
 */
class ShortcodeAssertionsTest extends ShortcodeExistsTestCase
{
	/**
	 * @covers \Pretzlaw\WPInt\Constraint\Shortcode\AbstractShortcodeConstaint
	 * @covers \Pretzlaw\WPInt\Constraint\Shortcode\ShortcodeExists
	 */
	public function testConstraintThatShortcodeExistsShouldSucceed()
	{
		$constraint = new ShortcodeExists($this->shortcodeTagsList);

		static::assertTrue($constraint->evaluate($this->shortcodeName, '', true));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::assertShortcodeExists()
	 */
	public function testAssertionThatShortcodeExistsDoesSucceed()
	{
		self::assertShortcodeExists($this->shortcodeName);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::assertShortcodeHasCallback()
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::getShortcodeCallback()
	 */
	public function testAssertionAboutCallbackDoesSucceed()
	{
		self::assertShortcodeHasCallback($this->shortcodeCallback, $this->shortcodeName);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::assertShortcodeHasCallback()
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::getShortcodeCallback()
	 */
	public function testAssertionAboutCallbackFailsOnWrongCallback()
	{
		$this->expectException(AssertionFailedError::class);

		self::assertShortcodeHasCallback('__return_false', $this->shortcodeName);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::assertShortcodeNotExists
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::getShortcodeCallback
	 */
	public function testAssertionsAboutShortcodeNotExistsFails()
	{
		$this->expectException(AssertionFailedError::class);

		self::assertShortcodeNotExists($this->shortcodeName);
	}
}
