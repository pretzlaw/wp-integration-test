<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Shortcode.php
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
 * @package   wp-integration-test
 * @copyright 2021 M. Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

namespace Pretzlaw\WPInt\Mocks;

use Mockery;
use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;
use Pretzlaw\WPInt\Mocks\Double\Shortcode as ShortcodeDouble;

/**
 * Shortcode
 */
class Shortcode implements ApplicableInterface, CleanUpInterface
{
	/**
	 * @var callable|null
	 */
	private $backup;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * Reference to list that shall be modified
	 *
	 * @var array<string, callable>
	 */
	private $shorcodeTags;

	/**
	 * Shortcode constructor.
	 *
	 * @param string $name Name of the shortcode that will be mocked
	 * @param array $shorcodeTags List of shortcodes where the mock should be placed
	 */
	public function __construct(string $name, array &$shorcodeTags)
	{
		$this->name = $name;
		$this->shorcodeTags =& $shorcodeTags;
		$this->backup = $shorcodeTags[$name] ?? null;
	}

	public function apply()
	{
		$mock = Mockery::mock(ShortcodeDouble::class);
		$this->shorcodeTags[$this->name] = [$mock, 'do_shortcode'];

		return $mock->shouldReceive('do_shortcode');
	}

	public function __invoke()
	{
		$this->shorcodeTags[$this->name] = $this->backup;

		if (null === $this->backup) {
			unset($this->shorcodeTags[$this->name]);
		}
	}
}
