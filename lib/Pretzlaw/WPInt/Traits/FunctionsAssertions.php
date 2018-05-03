<?php


namespace Pretzlaw\WPInt\Traits;


trait FunctionsAssertions {
	use FilterAssertions {
		mockFilter as _mockFilter;
	}

	protected function disableWpDie() {
		$this->_mockFilter( 'wp_die_handler' )->expects( $this->any() )->willReturn( 'time' );
		$this->_mockFilter( 'wp_die_xmlrpc_handler' )->expects( $this->any() )->willReturn( 'time' );
		$this->_mockFilter( 'wp_die_ajax_handler' )->expects( $this->any() )->willReturn( 'time' );
	}
}