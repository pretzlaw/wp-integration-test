<?php


namespace Pretzlaw\WPInt\Mocks;

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
     * @param null $metaValue
     * @param null $objectId
     */
	public function __construct(
		string $type,
		$metaKey,
		$metaValue = null,
		$objectId = null
	) {
		$this->type = $type;

		// Only check what is defined.
		$args = array_filter( [ $objectId, $metaKey, $metaValue ] );

		// Filter sends in "null" as current value.
		array_unshift( $args, null );

		parent::__construct( 'update_' . $type . '_metadata', true, $args );

        $this->metaKey = $metaKey;
    }

    protected function fixExceptionMessage(\Exception $e) {
		return sprintf( 'Updating %s-meta "%s" did not happen.', $this->type, $this->metaKey );
	}
}
