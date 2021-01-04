<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Mocks\ExpectedFilter;

trait FunctionsAssertions {
	protected function disableWpDie() {
        $wpDieFilter = ['wp_die_handler', 'wp_die_xmlrpc_handler', 'wp_die_ajax_handler'];
        foreach ($wpDieFilter as $item) {
            // Override functions with php internal function.
            $mock = new ExpectedFilter($item, 'time');

            $mock->expects($this->any())->willReturn(time());
            $this->wpIntMocks[] = $mock;

            $mock->addFilter();
              }
	}
}
