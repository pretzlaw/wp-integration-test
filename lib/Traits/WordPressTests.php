<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Summary of multiple traits
 *
 * LICENSE: This source file is created by the company around Mike Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package    pretzlaw/wp-integration-test
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @link       https://project.rmp-up.de/pretzlaw/wp-integration-test
 * @since      2018-12-27
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Traits;

use Mockery\Exception\InvalidCountException;
use PackageVersions\Versions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Warning;
use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;
use Pretzlaw\WPInt\Mocks\PostCondition;

/**
 * Simplify usage by gathering all traits in one alias
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-27
 */
trait WordPressTests
{
	/**
	 * @var callable[]
	 */
	protected $wpIntQueue = [];

	use ActionAssertions;
	use CacheAssertions;
	use \Pretzlaw\WPInt\Filter\FilterAssertions;
	use FunctionsAssertions;
	use MetaDataAssertions;
	use OptionAssertions;
	use PluginAssertions;
	use PostAssertions;

	use PostTypeAssertions;
	use ShortcodeAssertions;
	use UserAssertions;
	use WidgetAssertions;

	/**
	 * @postCondition
	 */
	public function wpIntPostConditions()
	{
		$postConditions = $this->wpIntQueue[PostCondition::class];
		$this->wpIntQueue[PostCondition::class] = [];

		foreach ($postConditions as $item) {
			/** @var PostCondition $item */
			try {
				$this->addToAssertionCount($item->verifyPostCondition());
			} catch (InvalidCountException $e) {
				// Transform Mockery exception to get the proper console output
				throw new AssertionFailedError($e->getMessage(), $e->getCode(), $e);
			}
		}
	}

	/**
	 * Clean up previous calls
	 *
	 * Cleans up previous tests after the test.
	 * Also clean up before the test just to be sure,
	 * if previous tests failed way to hard.
	 *
	 * @after
	 */
	public function wpIntCleanUp()
	{
		foreach ($this->wpIntQueue[CleanUpInterface::class] as $callback) {
			/** @var CleanUpInterface $callback */
			$callback();
		}

		if (version_compare(Versions::getVersion('phpunit/phpunit'), '9.1.0', '<')) {
			// Before PHP 9.1.0 there were no @postCondition tag so we trigger it manually
			$this->wpIntPostConditions();
		}

		$this->wpIntQueue[CleanUpInterface::class] = [];
	}

	/**
	 * Check and clean the queue
	 *
	 * @before
	 */
	public function wpIntCleanQueue()
	{
		$remaining = implode(', ', array_keys(array_filter($this->wpIntQueue)));
		if ($remaining) {
			throw new Warning('Previous test did not clean up: ' . $remaining);
		}

		$this->wpIntQueue = [
			CleanUpInterface::class => [],
			PostCondition::class => [],
		];
	}

	/**
	 * @param $subject
	 *
	 * @return mixed|null
	 */
	public function wpIntApply($subject)
	{
		// Prepend because clean-up does FIFO
		if ($subject instanceof CleanUpInterface) {
			array_unshift($this->wpIntQueue[CleanUpInterface::class], $subject);
		}

		if ($subject instanceof PostCondition) {
			$this->wpIntQueue[PostCondition::class][] = $subject;
		}

		if ($subject instanceof ApplicableInterface) {
			// todo Let the caller do this
			return $subject->apply();
		}

		return null;
	}
}
