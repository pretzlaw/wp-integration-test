<?php


namespace Pretzlaw\WPInt\Mocks\Classes;


use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * Trait WpQueryMocks
 * @package Pretzlaw\WPInt\Mocks\Classes
 *
 * @deprecated 0.2.0 This will be removed because this is no real feature - just a shortcut.
 */
trait WpQueryMocks {
	/**
	 * @param array $queryArgs
	 * @param array $result
	 *
	 * @return \PHPUnit\Framework\MockObject\MockObject|\WP_Query
	 */
	protected function mockWpQuery( array $queryArgs, array $result = [] ) {
		/** @var MockBuilder $mockBuilder */
		$mockBuilder = $this->getMockBuilder( \WP_Query::class );

		$query = $mockBuilder->enableProxyingToOriginalMethods()
		                     ->setMethods( [ 'get_posts' ] )
		                     ->getMock()
		;

		$query->expects( $this->any() )->method( 'get_posts' )->willReturn( $result );

		$query->query( $queryArgs );
		$query->parse_query();

		return $query;
	}
}
