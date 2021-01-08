<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PluginTestCase.php
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

namespace Pretzlaw\WPInt\Test;

use Pretzlaw\WPInt\Constraint\PluginIsActive;

/**
 * PluginTestCase
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class PluginTestCase extends TestCase
{
	/**
	 * @var PluginIsActive
	 */
	protected $pluginConstraint;

	/**
	 * @var string[]
	 */
	private $pluginList;

	/**
	 * @var string
	 */
	protected $activePluginSlug;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->activePluginSlug = 'akismet/akismet.php';
		$this->pluginList = [
			$this->activePluginSlug,
		];

		$this->pluginConstraint = new PluginIsActive($this->pluginList);

		$this->mockOption('active_plugins')->andReturn($this->pluginList);
	}
}
