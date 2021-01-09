<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * CheckPostTypeDefinitionTest.php
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

namespace Pretzlaw\WPInt\Test\Post\Type;

use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;
use Pretzlaw\WPInt\Test\Post\TypeTestCase;
use WP_Post_Type;

/**
 * CheckPostTypeDefinitionTest
 */
class ExistingPostTypeTestCase extends TypeTestCase
{
	/**
	 * @var array
	 */
	protected $postTypeLabels;

	/**
	 * @var WP_Post_Type
	 */
	protected $postTypeObject;

	/**
	 * @var array
	 */
	protected $postTypeData;

	/**
	 * @var string
	 */
	protected $postTypeName;

	/**
	 * @var string
	 */
	protected $postTypeDescription;

	/**
	 * @var string
	 */
	protected $postTypeMenuIcon;

	protected function compatSetUp()
	{
		parent::compatSetUp();

		$this->postTypeName = md5(uniqid('', true));

		global $wp_post_types;

		$this->postTypeDescription = uniqid('', true);
		$this->postTypeMenuIcon = uniqid('', true);

		$this->postTypeLabels = [
			'name' => uniqid('', true),
			'singular_name' => uniqid('', true),
			'add_new' => uniqid('', true),
			'add_new_item' => uniqid('', true),
			'edit_item' => uniqid('', true),
		];

		$this->postTypeData = [
			'show_ui' => false,
			'show_in_menu' => false,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'description' => $this->postTypeDescription,
			'menu_icon' => $this->postTypeMenuIcon,
			'public' => false,
			'labels' => $this->postTypeLabels,
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
