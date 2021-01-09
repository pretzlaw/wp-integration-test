<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * TypeTestCase.php
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

namespace Pretzlaw\WPInt\Test\Post;

use Pretzlaw\WPInt\Test\TestCase;
use WP_Post_Type;

/**
 * TypeTestCase
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class TypeTestCase extends TestCase
{
	protected $postTypeObject;
	protected $postTypeData;
	protected $postTypeName;
	protected $postTypeDescription;
	protected $postTypeMenuIcon;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->postTypeName = md5(uniqid('', true));

		global $wp_post_types;

		$this->postTypeDescription = uniqid('', true);
		$this->postTypeMenuIcon = uniqid('', true);

		$this->postTypeData = [
			'show_ui' => false,
			'show_in_menu' => false,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'description' => $this->postTypeDescription,
			'menu_icon' => $this->postTypeMenuIcon,
			'public' => false,
		];

		$this->postTypeObject = new WP_Post_Type($this->postTypeName, $this->postTypeData);

		$wp_post_types[$this->postTypeName] = $this->postTypeObject;
	}

	protected function compatTearDown()
	{
		global $wp_post_types;

		if (isset($wp_post_types[$this->postTypeName])) {
			unset($wp_post_types[$this->postTypeName]);
		}
	}
}
