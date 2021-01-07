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
    protected $wpIntCleanUp = [];

    use ActionAssertions;
    use CacheAssertions;
    use \Pretzlaw\WPInt\Filter\FilterAssertions;
    use FunctionsAssertions;
    use MetaDataAssertions;
    use PluginAssertions;
    use PostAssertions;

    use PostTypeAssertions;
    use ShortcodeAssertions;
    use UserAssertions;
    use WidgetAssertions;

	/**
	 * Clean up previous calls
	 *
	 * Cleans up previous tests after the test.
	 * Also clean up before the test just to be sure,
	 * if previous tests failed way to hard.
	 *
	 * @after
	 * @before
	 */
	public function wpIntCleanUp()
	{
		foreach ($this->wpIntCleanUp as $callback) {
			if ($callback instanceof PostCondition) {
				$this->addToAssertionCount(
					$callback->verifyPostCondition()
				);
			}

			$callback();
		}

		$this->wpIntCleanUp = [];
	}

	/**
	 * @param CleanUpInterface $cleanUp
	 *
	 * @return mixed|null
	 */
	public function wpIntApply(CleanUpInterface $cleanUp)
	{
		// Prepend because clean-up does FIFO
		// todo only prepend if interface is given
		array_unshift($this->wpIntCleanUp, $cleanUp);

		if ($cleanUp instanceof ApplicableInterface) {
			return $cleanUp->apply();
		}

		return null;
	}
}
