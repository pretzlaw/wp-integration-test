<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ShortcodeExistsTest.php
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

namespace Pretzlaw\WPInt\Test\Shortcode;

use Pretzlaw\WPInt\Test\ShortcodeTestCase;

/**
 * ShortcodeExistsTest
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class ShortcodeExistsTestCase extends ShortcodeTestCase
{
	/**
	 * @var array
	 */
	protected $shortcodeTagsList;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->shortcodeTagsList = [
			// with some random just to have some data
			md5(uniqid('', true)) => '__return_false',
			md5(uniqid('', true)) => '__return_false',
			$this->shortcodeName => $this->shortcodeCallback,
			md5(uniqid('', true)) => '__return_false',
			md5(uniqid('', true)) => '__return_false',
		];

		add_shortcode($this->shortcodeName, $this->shortcodeCallback);
	}

	protected function compatTearDown()
	{
		remove_shortcode($this->shortcodeName);

		parent::compatTearDown();
	}
}
