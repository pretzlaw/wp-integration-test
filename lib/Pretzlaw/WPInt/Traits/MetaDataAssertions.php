<?php


namespace Pretzlaw\WPInt\Traits;


use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument;
use Pretzlaw\WPInt\ArgumentSwitch;
use Pretzlaw\WPInt\ClutterInterface;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\ExpectedMetaUpdate;
use Pretzlaw\WPInt\Mocks\MetaData;

trait MetaDataAssertions {
    private $isRegistered = false;

    private $mockedMetaData = [];

    /**
     * @var ExpectedFilter[]
     */
    private $registeredFilter = [];

    protected function expectUpdateMeta($type, $metaKey, $metaValue, $objectId = null)
    {
        $expectedFilter = new ExpectedMetaUpdate(
            $type,
            $metaKey,
            $metaValue,
            $objectId
        );

        $expectedFilter->expects($this->atLeastOnce());
        $expectedFilter->addFilter();

        $this->wpIntMocks[] = $expectedFilter;
    }

    protected function expectUpdatePostMeta($metaKey, $metaValue = null, $objectId = null)
    {
        $this->expectUpdateMeta('post', $metaKey, $metaValue, $objectId);
    }

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
     * @param string $metaValue the value that shall be returned.
     * @param null|int $objectId Which entity ID to override. Leave NULL to override all.
     * @param null|bool $single Whether to override the single or general form.
     */
    protected function mockMetaData($type, $metaKey, $metaValue, $objectId = null, $single = null)
    {
        if (false === $single && !is_array($metaValue)) {
            // Non-single returns shall always be an array.
            $metaValue = [$metaValue];
        }

        $switch = new ArgumentSwitch(new ReturnArgument(0));

        $switch->add($metaValue, [
            new IsAnything(),
            $objectId ?? new IsAnything(),
            $metaKey,
            $single ?? new IsAnything(),
        ]);

        $metaData = new MetaData($type, $metaKey, $objectId, $single);
        $metaData->expects($this->any())->willReturnCallback($switch);

        $metaData->addFilter();
        $this->registerMockObject($metaData);
    }

    protected function mockPostMeta($metaKey, $metaValue, $postId = null)
    {
        $this->mockMetaData('post', $metaKey, $metaValue, $postId);
    }

    protected function mockUserMeta($metaKey, $metaValue, $postId = null)
    {
        $this->mockMetaData('user', $metaKey, $metaValue, $postId);
    }
}
