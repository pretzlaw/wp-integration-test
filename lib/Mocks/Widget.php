<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Widget.php
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
 * @package    wp-integration-test
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @since      2020-01-11
 */

namespace Pretzlaw\WPInt\Mocks;

use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;

/**
 * Widget
 */
class Widget implements ApplicableInterface, CleanUpInterface
{
	private $hash;
	private $target;
	private $widgetList;

	/**
	 * Widget constructor.
	 *
	 * @param \WP_Widget $widgetOrMock
	 */
	public function __construct($widgetOrMock, &$widgetList)
	{
		$this->target = $widgetOrMock;
		$this->widgetList =& $widgetList;

		$this->hash = spl_object_hash($this->target);
	}

	public function apply()
	{
		$this->widgetList[$this->hash] = $this->target;
	}

	public function __invoke()
	{
		if (isset($this->widgetList[$this->hash])) {
			unset($this->widgetList[$this->hash]);
		}
	}
}
