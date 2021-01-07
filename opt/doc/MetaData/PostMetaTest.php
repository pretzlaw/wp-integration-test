<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MockPostMetaTest.php
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

use Pretzlaw\WPInt\Mocks\MetaData;
use Pretzlaw\WPInt\Test\MetaDataTestCase;

/**
 * MockPostMetaTest
 *
 * @covers \Pretzlaw\WPInt\Mocks\MetaData
 */
class PostMetaTest extends MetaDataTestCase
{
	private $metaKey;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->metaType = 'post';
		$this->metaKey = uniqid('', true);

		$this->metaDataMock = new MetaData($this->metaType, $this->metaKey, $this->objectId);
	}

	/**
	 * @group integration
	 */
	public function testCanMockPostMeta()
	{
		$newValue = uniqid('', true);
		$this->metaDataMock->apply()->andReturn($newValue);

		static::assertEquals($newValue, get_post_meta($this->objectId, $this->metaKey, true));
	}

	/**
	 * @group acceptance
	 * @covers \Pretzlaw\WPInt\Traits\MetaDataAssertions::mockPostMeta()
	 */
	public function testApiDoesMockPostMeta()
	{
		$metaKey = uniqid('', true);
		$newValue = uniqid('', true);

		$this->mockPostMeta($metaKey, $this->objectId)->andReturn($newValue);

		static::assertEquals($newValue, get_post_meta($this->objectId, $metaKey, true));
	}

	/**
	 * @group acceptance
	 *
	 * @covers \Pretzlaw\WPInt\Mocks\MetaData::__invoke()
	 * @covers \Pretzlaw\WPInt\Traits\MetaDataAssertions::mockPostMeta()
	 */
	public function testApiMockedPostMetaRecoversAfterTest()
	{
		$metaKey = uniqid('', true);
		$newValue = uniqid('', true);

		$this->mockPostMeta($metaKey, $this->objectId)->andReturn($newValue);

		static::assertEquals($newValue, get_post_meta($this->objectId, $metaKey, true));

		$this->wpIntCleanUp();

		static::assertEmpty(get_post_meta($this->objectId, $metaKey, true));
	}
}
