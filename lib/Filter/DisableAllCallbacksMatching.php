<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * RemoveAllCallbacksMatching.php
 *
 * LICENSE: This source file is created by the company around M. Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package   wp-integration-test
 * @copyright 2021 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Filter;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;
use WP_Hook;

/**
 * RemoveAllCallbacksMatching
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class DisableAllCallbacksMatching implements ApplicableInterface, CleanUpInterface
{
	private $backups = [];
	private $fakeFilter;
	/**
	 * @var array
	 */
	private $pattern;
	private $targets = [];
	private $wpFilter;

	public function __construct($pattern, &$wpFilter)
	{
		// Ensure arrays starting at zero.
		$this->pattern = array_values((array) $pattern);

		// Everything must be a constraint
		foreach ($this->pattern as $key => $value) {
			if (false === $value instanceof Constraint) {
				$this->pattern[$key] = new IsIdentical($value);
			}
		}

		$this->wpFilter =& $wpFilter;
	}

	public function __invoke()
	{
		foreach ($this->backups as $key => $previousCallback) {
			// Use references (in ->target) to recover previous callback
			$this->targets[$key] = $previousCallback;
		}
	}

	public function apply()
	{
		$patternCount = count($this->pattern);

		foreach ($this->wpFilter as &$hook) {
			$hooks =& $hook;
			if ($hook instanceof WP_Hook) {
				$hooks =& $hook->callbacks;
			}

			/** @var WP_Hook $hook */
			foreach ($hooks as &$callbacks) {
				foreach ($callbacks as &$priority) {
					$this->parseCallback($priority, $patternCount);
				}
			}
		}
	}

	/**
	 * @return FakeFilter
	 */
	private function getFakeFilter(): FakeFilter
	{
		if (null === $this->fakeFilter) {
			$this->fakeFilter = new FakeFilter();
		}

		return $this->fakeFilter;
	}

	private function overrideFilter(&$filterValue)
	{
		$hash = uniqid('', true);

		$this->backups[$hash] = $filterValue;
		$this->targets[$hash] =& $filterValue;

		$filterValue = [
			'function' => $this->getFakeFilter(),
			'accepted_args' => 1,
		];
	}

	private function parseCallback(&$callback, $patternCount)
	{
		// Ensure array keys starting at zero.
		$func = array_values((array) $callback['function']);

		if (count($func) !== $patternCount) {
			return;
		}

		foreach ($this->pattern as $key => $constaint) {
			/** @var Constraint $constaint */
			if (false === $constaint->evaluate($func[$key], '', true)) {
				// Not our callback
				return;
			}
		}

		// Replace filter with a double
		// This way we can recover the filter after the test at its excact point.
		$this->overrideFilter($callback);
	}
}
