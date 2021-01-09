<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * UnknownPostTypeTest.php
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

namespace Pretzlaw\WPInt\Test\Post\Type;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Constraint\Post\Type\IsRegistered;
use Pretzlaw\WPInt\Test\Post\TypeTestCase;
use RuntimeException;
use WP_Post_Type;

/**
 * UnknownPostTypeTest
 */
class UnknownPostTypeTest extends TypeTestCase
{
	/**
	 * @group unit
	 *
	 * @covers \Pretzlaw\WPInt\Constraint\Post\Type\IsRegistered
	 */
	public function testConstraintThatItExistsWillFail()
	{
		$assertion = new IsRegistered(['foo' => new WP_Post_Type('foo')]);

		static::assertFalse($assertion->evaluate(uniqid('', true), '', true));
	}

	/**
	 * @group integration
	 * @group acceptance
	 *
	 * @covers \Pretzlaw\WPInt\Traits\PostTypeAssertions::assertPostTypeRegistered
	 */
	public function testAssertingItExistsWillThrowError()
	{
		$postTypeName = uniqid('', true);

		$this->expectException(AssertionFailedError::class);

		self::assertPostTypeRegistered($postTypeName);
	}

	/**
	 * @group acceptance
	 *
	 * @covers \Pretzlaw\WPInt\Constraint\Post\Type\IsRegistered::toString
	 * @covers \Pretzlaw\WPInt\Traits\PostTypeAssertions::assertPostTypeRegistered
	 */
	public function testAssertingItExistsShouldShowErrorMessage()
	{
		$postTypeName = uniqid('', true);

		$this->expectException(AssertionFailedError::class);
		$this->expectExceptionMessageRegExp(
			'/' . preg_quote($postTypeName, '/') . '[\'"] is a registered post-type./'
		);

		self::assertPostTypeRegistered($postTypeName);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\PostTypeAssertions::assertPostTypeArgs()
	 * @covers \Pretzlaw\WPInt\Traits\PostTypeAssertions::getPostTypeObject()
	 */
	public function testAssertingDefinitionThrowsError()
	{
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Post type has no object - maybe not registered yet or typo?');

		static::assertPostTypeArgs(uniqid('', true), []);
	}
}
