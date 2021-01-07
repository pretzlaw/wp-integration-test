<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MetaDataTestCase.php
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

use Pretzlaw\WPInt\Mocks\MetaData;

/**
 * MetaDataTestCase
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class MetaDataTestCase extends TestCase
{
	protected $metaDataMock;
	protected $metaType;
	protected $objectId;
	private $metaValue;

	protected function assertMetaDataIsUnchanged($metaKey)
	{
		$this->assertMetaData(
			$this->metaValue[$metaKey],
			$this->metaType,
			$this->objectId,
			$metaKey
		);
	}

	protected function compatSetUp()
	{
		parent::compatSetUp();

		// Mocking meta-data fallback one step later than the actual mock
		$this->objectId = 1;
		$this->metaType = uniqid('', true);
		$this->metaValue = [
			// encapsulated in arrays because this is how it would come from cache/database
			'existing' => [uniqid('', true)],
			'not_overridden' => [uniqid('', true)],
		];

		$this->mockCache()
			->shouldReceive('get', $this->objectId, $this->metaType . '_meta')
			->andReturn($this->metaValue);

		$this->metaDataMock = new MetaData($this->metaType, 'existing', $this->objectId);
	}

	/**
	 * @param mixed $expected
	 * @param string $metaKey
	 *
	 * @todo that is a nice shortcut for the traits, create MetaDataAssertions::assertMetaDataEquals()
	 */
	protected function assertMetaData($expected, string $metaType, $objectId, string $metaKey = '', bool $single = false)
	{
		return static::assertEquals(
			$expected,
			get_metadata($metaType, $objectId, $metaKey, $single)
		);
	}
}
