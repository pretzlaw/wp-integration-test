<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\ClutterInterface;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\ExpectedMetaUpdate;

trait MetaDataAssertions {
	private $isRegistered = false;

	private $mockedMetaData = [];

	/**
	 * @var ExpectedFilter[]
	 */
	private $registeredFilter = [];

	/**
	 * @var ClutterInterface[]
	 */
	private $wpIntegrationClutter = [];

    /**
     * @deprecated 0.2.0
     */
	protected function assertPostConditions() {
		$this->mockedMetaData = [];
		remove_filter( 'get_post_metadata', [ $this, 'overridePostMetaData' ] );

		foreach ( $this->wpIntegrationClutter as $item ) {
			if ( false === $item instanceof ClutterInterface ) {
				continue;
			}

			$item->tearDown();
		}
	}

	protected function expectUpdateMeta( $type, $metaKey, $metaValue, $objectId = null ) {
		$expectedFilter = new ExpectedMetaUpdate(
			$type,
			$metaKey,
			$metaValue,
			$objectId
		);

		$expectedFilter->expects($this->atLeastOnce());
		$expectedFilter->addFilter();
		$this->registerMockObject($expectedFilter);

		$this->wpIntegrationClutter[] = $expectedFilter;
	}

	protected function expectUpdatePostMeta( $metaKey, $metaValue = null, $objectId = null ) {
		$this->expectUpdateMeta( 'post', $metaKey, $metaValue, $objectId );
	}

	/**
	 * @param string $type      e.g. "post" for post-meta
	 * @param string $metaKey
	 * @param string $metaValue the value that shall be returned.
	 * @param int    $postId
	 */
	protected function mockMetaData( $type, $metaKey, $metaValue, $postId ) {
		if ( ! isset( $this->mockedMetaData[ $type ] ) ) {
			$this->mockedMetaData[ $type ] = [];
		}

		if ( ! isset( $this->mockedMetaData[ $type ][ $metaKey ] ) ) {
			$this->mockedMetaData[ $type ][ $metaKey ] = [];
		}

		$this->mockedMetaData[ $type ] [ $metaKey ][ $postId ] = $metaValue;
	}

	protected function mockPostMeta( $metaKey, $metaValue, $postId = null ) {
		if ( ! $this->isRegistered ) {
			\add_filter( 'get_post_metadata', [ $this, 'overridePostMetaData' ], 10, 3 );
			$this->isRegistered = true;
		}

		$this->mockMetaData( 'post', $metaKey, $metaValue, $postId );
	}

	/**
	 * @param $type
	 * @param $currentValue
	 * @param $objectId
	 * @param $metaKey
	 *
	 * @return mixed
	 */
	private function overrideMetaData( $type, $currentValue, $objectId, $metaKey ) {
		if ( ! isset( $this->mockedMetaData[ $type ] ) ) {
			// Got nothing for this type.
			return $currentValue;
		}

		if ( ! isset( $this->mockedMetaData[ $type ][ $metaKey ] ) ) {
			// Got nothing for this meta data.
			return $currentValue;
		}

		if ( isset( $this->mockedMetaData[ $type ][ $metaKey ][ $objectId ] ) ) {
			// Got specific value for that.
			return $this->mockedMetaData[ $type ][ $metaKey ][ $objectId ];
		}

		if ( isset( $this->mockedMetaData[ $type ][ $metaKey ][ null ] ) ) {
			// Got general value for that.
			return $this->mockedMetaData[ $type ][ $metaKey ][ null ];
		}

		// Just got mocks for others so we keep it as it is.
		return $currentValue;
	}

    /**
     * @param $currentValue
     * @param $objectId
     * @param $metaKey
     * @return mixed
     *
     * @deprecated 0.2.0 This will be because expectations are properly registered now.
     */
	public function overridePostMetaData( $currentValue, $objectId, $metaKey ) {
		return $this->overrideMetaData( 'post', $currentValue, $objectId, $metaKey );
	}
}
