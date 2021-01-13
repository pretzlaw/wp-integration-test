<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * DefinitionTest.php
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

namespace Pretzlaw\WPInt\Test\Post\Type\ExistingPostType;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Test\Post\Type\ExistingPostTypeTestCase;

/**
 * DefinitionTest
 */
class DefinitionTest extends ExistingPostTypeTestCase
{
	/**
	 * @covers \Pretzlaw\WPInt\Constraint\AssocArrayHasSubset
	 * @covers \Pretzlaw\WPInt\Traits\PostTypeAssertions::assertPostTypeArgs()
	 *
	 * @group acceptance
	 */
	public function testAssertingDifferentPostTypeDefinitionThrowsError()
	{
		$wrongDefinition = $this->postTypeData;

		// changing one thing
		$wrongDefinition['description'] = uniqid('', true);

		$this->expectException(AssertionFailedError::class);
		static::assertPostTypeArgs($this->postTypeName, $wrongDefinition);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Constraint\AssocArrayHasSubset
	 * @covers \Pretzlaw\WPInt\Traits\PostTypeAssertions::assertPostTypeArgs
	 */
	public function testCanAssertSubsetOfPostTypeDefinition()
	{
		static::assertPostTypeArgs($this->postTypeName, [
			'menu_icon' => $this->postTypeMenuIcon,
		]);

		static::assertPostTypeArgs($this->postTypeName, [
			'description' => $this->postTypeDescription,
		]);

		static::assertPostTypeArgs($this->postTypeName, [
			'description' => $this->postTypeDescription,
			'menu_icon' => $this->postTypeMenuIcon,
		]);
	}
}
