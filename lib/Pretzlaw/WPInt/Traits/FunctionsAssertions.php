<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Mocks\ExpectedFilter;

trait FunctionsAssertions {
	protected function disableWpDie() {
		$this->_mockFilter( 'wp_die_handler' )->expects( $this->any() )->willReturn( 'time' );
		$this->_mockFilter( 'wp_die_xmlrpc_handler' )->expects( $this->any() )->willReturn( 'time' );
		$this->_mockFilter( 'wp_die_ajax_handler' )->expects( $this->any() )->willReturn( 'time' );
	}

	/**
	 * @param string $filterName
	 *
	 * @return ExpectedFilter
	 */
	protected function _functionMockFilter( $filterName ) {
		$mock = new ExpectedFilter( $filterName );

		$this->registerMockObject($mock);
		$mock->addFilter();

		return $mock;
	}
}