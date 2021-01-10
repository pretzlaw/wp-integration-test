<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MockShortcodeTest.php
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

use Mockery\CompositeExpectation;
use Mockery\Expectation;
use Mockery\MockInterface;
use Pretzlaw\WPInt\Mocks\Shortcode;
use Pretzlaw\WPInt\Test\Shortcode\ShortcodeExistsTestCase;

/**
 * MockShortcodeTest
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class MockShortcodeTest extends ShortcodeExistsTestCase
{
	private $mockShortcode;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->mockShortcode = new Shortcode($this->shortcodeName, $this->shortcodeTagsList);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Mocks\Shortcode
	 */
	public function testMockShortcodeOverridesExisting()
	{
		$this->mockShortcode->apply();

		static::assertNotEquals($this->shortcodeCallback, $this->shortcodeTagsList[$this->shortcodeName]);
		static::assertInstanceOf(MockInterface::class, $this->shortcodeTagsList[$this->shortcodeName][0]);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\ShortcodeAssertions::mockShortcode()
	 */
	public function testShortcodeMockCanChangeReturnValue()
	{
		$expectedReturn = uniqid('', true);

		$this->mockShortcode($this->shortcodeName)
			->andReturn($expectedReturn);

		static::assertEquals($expectedReturn, do_shortcode('[' . $this->shortcodeName . ']'));
	}

	/**
	 * @covers \Pretzlaw\WPInt\Mocks\Shortcode
	 */
	public function testMockShortcodeCanRecoverExisting()
	{
		$this->testMockShortcodeOverridesExisting();

		$this->mockShortcode->__invoke();

		static::assertEquals($this->shortcodeCallback, $this->shortcodeTagsList[$this->shortcodeName]);
	}
}
