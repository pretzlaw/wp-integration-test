<?php


namespace Pretzlaw\WPInt\Mocks\Classes;


use PHPUnit\Framework\MockObject\MockBuilder;

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
