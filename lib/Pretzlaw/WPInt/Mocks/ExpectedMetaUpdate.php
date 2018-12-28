<?php


namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\Constraint\IsAnything;

class ExpectedMetaUpdate extends ExpectedFilter {
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $metaKey;

    /**
     * ExpectedMetaUpdate constructor.
     *
     * @param string $type
     * @param string $metaKey
     * @param null $metaValue Watch for specific meta value.
     * @param null $objectId
     */
    public function __construct(
        string $type,
        $metaKey,
        $metaValue = null,
        $objectId = null
    )
    {
        if (null === $objectId) {
            $objectId = new IsAnything();
        }

        parent::__construct(
            'update_' . $type . '_metadata',
            true,
            // Filter sends in "null" as current value.
            [null, $objectId, $metaKey, $metaValue]
        );

        $this->type = $type;
        $this->metaKey = $metaKey;
    }

    protected function fixExceptionMessage(\Exception $e) {
        return sprintf('Updating %s-meta "%s" did not happen.', $this->type, $this->metaKey);
    }
}
