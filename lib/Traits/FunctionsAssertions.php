<?php

declare(strict_types=1);

namespace Pretzlaw\WPInt\Traits;

use Pretzlaw\WPInt\Mocks\Filter;

trait FunctionsAssertions
{
	protected function getWpDieFilter(): array
	{
		return [
			'wp_die_handler',
			'wp_die_ajax_handler',
			'wp_die_json_handler',
			'wp_die_jsonp_handler',
			'wp_die_xml_handler',
			'wp_die_xmlrpc_handler',
		];
	}

	protected function disableWpDie()
	{
		foreach ($this->getWpDieFilter() as $item) {
			$this->wpIntApply(new Filter($item))->andReturn('time');
		}
	}
}
