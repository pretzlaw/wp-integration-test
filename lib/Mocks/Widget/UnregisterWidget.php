<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * UnregisterWidget.php
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

namespace Pretzlaw\WPInt\Mocks\Widget;

use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;
use WP_Widget;

/**
 * UnregisterWidget
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class UnregisterWidget implements ApplicableInterface, CleanUpInterface
{
	/**
	 * @var object[]|null
	 */
	private $backup = [];

	/**
	 * @var string
	 */
	private $widgetId;

	/**
	 * @var WP_Widget[]
	 */
	private $widgetsList;

	public function __construct(&$widgetsList, $widgetId)
	{
		$this->widgetsList =& $widgetsList;
		$this->widgetId = $widgetId;
	}


	public function apply()
	{
		foreach ((array) $this->widgetsList as $widgetKey => $widget) {
			if ($this->widgetId === $widget->id_base) {
				$this->backup[$widgetKey] = $widget;

				unset($this->widgetsList[$widgetKey]);
			}
		}
	}

	public function __invoke()
	{
		if ([] === $this->backup) {
			// got nothing to recover (or already recovered)
			return;
		}

		foreach ($this->backup as $widgetKey => $backupWidget) {
			$this->widgetsList[$widgetKey] = $backupWidget;

			unset($this->backup[$widgetKey]);
		}

		$this->backup = [];
	}
}
