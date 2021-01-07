<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MultipleEntitiesTest.php
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

use Mockery\Matcher\Any;
use Pretzlaw\WPInt\Test\TestCase;

/**
 * MultipleEntitiesTest
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class MultipleEntitiesTest extends TestCase
{
	private $metaKey;
	private $metaType;
	private $mockValue;

	protected function compatSetUp()
	{
		$this->metaType = uniqid('', true);
		$this->metaKey = uniqid('', true);
		$this->mockValue = uniqid('', true);
		$this->mockMetaData($this->metaType, $this->metaKey, new Any())->andReturn([$this->mockValue]);
	}

	public function randomObjectIds()
	{
		return [
			[random_int(100, 9000)],
			[random_int(100, 9000)],
			[random_int(100, 9000)],
			[random_int(100, 9000)],
			[random_int(100, 9000)],
			[random_int(100, 9000)],
		];
	}

	/**
	 * @group acceptance
	 * @dataProvider randomObjectIds
	 *
	 * @covers \Pretzlaw\WPInt\Traits\MetaDataAssertions::mockMetaData()
	 */
	public function testMockMetaDataOfAnyEntity(int $objectId)
	{
		static::assertEquals($this->mockValue, get_metadata($this->metaType, $objectId, $this->metaKey, true));
	}
}
