<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MultipleSpecificObjectIdsTest.php
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

use Mockery\Matcher\AnyOf;
use Pretzlaw\WPInt\Test\TestCase;

/**
 * MultipleSpecificObjectIdsTest
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class MultipleSpecificObjectIdsTest extends TestCase
{
	const BARRIER = 1337;
	private $metaKey;
	/**
	 * @var string
	 */
	private $metaType;
	private $metaValue;
	/**
	 * @var int[]
	 */
	private $objectIds;

	protected function compatSetUp()
	{
		$this->metaType = uniqid('', true);
		$this->metaKey = uniqid('', true);
		$this->metaValue = uniqid('', true);
		$this->objectIds = [random_int(1, self::BARRIER), random_int(1, self::BARRIER), random_int(1, self::BARRIER)];
		$this->mockMetaData($this->metaType, $this->metaKey, new AnyOf($this->objectIds))
			->andReturn($this->metaValue);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\MetaDataAssertions::mockMetaData()
	 */
	public function testMocksMetaDataOfSomeObjectIds()
	{
		foreach ($this->objectIds as $objectId) {
			static::assertEquals($this->metaValue, get_metadata($this->metaType, $objectId, $this->metaKey, true));

			// Some other object does not have that value
			static::assertNotEquals(
				$this->metaValue,
				get_metadata($this->metaType, $objectId + self::BARRIER, $this->metaKey, true)
			);
		}
	}
}
