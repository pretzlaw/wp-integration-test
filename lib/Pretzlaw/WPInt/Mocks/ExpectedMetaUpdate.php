<?php


namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\TestCase;

class ExpectedMetaUpdate extends ExpectedFilter {
	/**
	 * @var string
	 */
	private $type;

	public function __construct(
		$testCase,
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

		parent::__construct( $testCase, 'update_' . $type . '_metadata', true, $args );
	}

	public function getErrorMessage() {
		return sprintf( 'Updating %s-meta "%s" did not happen.', $this->type, $this->args[1] );
	}
}
