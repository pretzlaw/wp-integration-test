<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * UndefinedFieldsTest.php
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

namespace Pretzlaw\WPInt\Test\MetaData;

use Mockery\Matcher\AndAnyOtherArgs;
use Mockery\Matcher\Any;
use Pretzlaw\WPInt\Test\MetaDataTestCase;

/**
 * UndefinedFieldsTest
 *
 * @covers \Pretzlaw\WPInt\Mocks\MetaData
 */
class UndefinedFieldsTest extends MetaDataTestCase
{

	/**
	 * @var string
	 */
	private $randomMetaKey;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->randomMetaKey = uniqid('', true);
	}

	/**
	 * @throws \Exception
	 * @group integration
	 */
	public function testDoesMockMetaValueOfUndefinedField()
	{
		$this->metaDataMock
			->apply()
			->with(new Any(), new Any(), $this->randomMetaKey, new AndAnyOtherArgs())
			->andReturn(['battery']);

		$this->assertMetaData('battery', $this->metaType, random_int(1337, 9001), $this->randomMetaKey, true);
	}

	/**
	 * @group integration
	 */
	public function testDoesRecoverUndefinedFieldToNullAfterTest()
	{
		$this->testDoesMockMetaValueOfUndefinedField();

		$this->metaDataMock->__invoke();

		$this->assertMetaData(null, $this->metaType, random_int(1337, 9001), $this->randomMetaKey, true);
	}
}
