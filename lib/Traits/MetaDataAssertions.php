<?php

declare(strict_types=1);

namespace Pretzlaw\WPInt\Traits;

use Mockery\Expectation;
use Pretzlaw\WPInt\ArgumentSwitch;
use Pretzlaw\WPInt\ClutterInterface;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\MetaData;

trait MetaDataAssertions {
	/**
	 * Mock some meta data
	 *
	 * NOTE: When using the AnyInvocationMocker it throws an exception when the arguments mismatch.
	 *       This happens often within a filter because it gets arguments from plenty uncontrollable directions.
	 *       We cover this using a strategy-pattern in the ArgumentSwitch
	 *       and refrain from returning the mock as long as there is no solution on the PHPUnit side.
	 *
	 * @param string $type e.g. "post" for post-meta
	 * @param string $metaKey The meta-key to override.
	 * @param null|int $objectId Which entity ID to override. Leave NULL to override all.
	 *
	 * @return Expectation|null
	 */
    protected function mockMetaData($type, $metaKey, $objectId = null)
	{
		return $this->wpIntApply(new MetaData($type, $metaKey, $objectId));
    }

	/**
	 * @param $metaKey
	 * @param null $postId
	 *
	 * @return Expectation|null
	 */
    protected function mockPostMeta($metaKey, $postId = null)
    {
        return $this->mockMetaData('post', $metaKey, $postId);
    }

	/**
	 * @param $metaKey
	 * @param null $postId
	 *
	 * @return Expectation|null
	 */
    protected function mockUserMeta($metaKey, $postId = null)
    {
        return $this->mockMetaData('user', $metaKey, $postId);
    }
}
