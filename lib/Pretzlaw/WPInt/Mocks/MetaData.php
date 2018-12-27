<?php


namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\MockObject\InvocationMocker;

class MetaData extends ExpectedFilter
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $metaKey;

    /**
     * @var InvocationMocker|null
     */
    private $invocationMocker;

    /**
     * ExpectedMetaUpdate constructor.
     *
     * @see \get_metadata() "get_{$meta_type}_metadata" filter.
     *
     * @param string $type
     * @param string $metaKey
     * @param null $metaValue Watch for specific meta value.
     * @param null $objectId
     */
    public function __construct(
        string $type,
        $metaKey,
        $objectId = null,
        $single = null
    )
    {
        if (null === $objectId) {
            $objectId = new IsAnything();
        }

        if (null === $single) {
            $single = new IsAnything();
        }

        parent::__construct(
            'get_' . $type . '_metadata',
            true,
            // Filter sends in "null" as current value.
            [null, $objectId, $metaKey, $single]
        );

        $this->type = $type;
        $this->metaKey = $metaKey;
    }

    protected function fixExceptionMessage(\Exception $e)
    {
        return sprintf('%s-meta "%s" was not read.', $this->type, $this->metaKey);
    }
}
