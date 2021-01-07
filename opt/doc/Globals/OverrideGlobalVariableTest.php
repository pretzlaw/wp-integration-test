<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * OverrideGlobalVariableTest.php
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

namespace Pretzlaw\WPInt\Test\Globals;

use Pretzlaw\WPInt\Mocks\Variable;
use Pretzlaw\WPInt\Test\GlobalsTestCase;

/**
 * OverrideGlobalVariableTest
 *
 * @covers \Pretzlaw\WPInt\Mocks\Variable
 */
class OverrideGlobalVariableTest extends GlobalsTestCase
{
	/**
	 * @var Variable
	 */
	private $mockGlobalVariable;
	/**
	 * @var string
	 */
	private $temporaryValue;
	/**
	 * @var string
	 */
	private $variableName;
	/**
	 * @var mixed
	 */
	private $variableValue;

	protected function compatSetUp()
	{
		$this->variableName = '_' . sha1(uniqid('', true));
		$this->variableValue = uniqid('', true);
		$this->temporaryValue = uniqid('', true);

		$GLOBALS[$this->variableName] = $this->variableValue;

		$this->mockGlobalVariable = new Variable($GLOBALS[$this->variableName], $this->temporaryValue);

		$this->mockGlobalVariable->apply();
	}

	/**
	 * @group unit
	 */
	public function testGlobalVariableIsOverridden()
	{
		global ${$this->variableName};

		static::assertNotEquals($this->variableValue, ${$this->variableName});
		static::assertEquals($this->temporaryValue, ${$this->variableName});
	}

	public function testGlobalVariableWillBeRecovered()
	{
		global ${$this->variableName};

		static::assertNotEquals($this->variableValue, ${$this->variableName});

		$this->mockGlobalVariable->verifyPostCondition();

		static::assertEquals($this->variableValue, ${$this->variableName});
	}
}
