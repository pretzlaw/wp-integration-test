<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * WidgetAssertions.php
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
 * @since      2020-01-09
 */

namespace Pretzlaw\WPInt\Traits;

use Mockery\Expectation;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\LogicalNot;
use Pretzlaw\WPInt\Constraint\MatchesConstraint;
use Pretzlaw\WPInt\Constraint\Shortcode\ShortcodeExists;
use Pretzlaw\WPInt\Mocks\Shortcode;

trait ShortcodeAssertions
{
    public static function assertShortcodeExists(string $shortcode, $message = '')
    {
        static::assertThat($shortcode, new ShortcodeExists(static::getAllShortcodes()), $message);
    }

    public static function assertShortcodeHasCallback($expectedCallback, string $shortcode, $message = '')
    {
        if ('' === $message) {
            $message = sprintf('Failed asserting that shortcode "%s" has the expected callback', $shortcode);
        }

        $callback = static::getShortcodeCallback($shortcode);

        if (null === $callback) {
            throw new AssertionFailedError(sprintf('Shortcode "%s" does not exist', $shortcode));
        }

        static::assertThat($callback, new MatchesConstraint($expectedCallback), $message);
    }

    public static function assertShortcodeNotExists(string $shortcode, $message = '')
    {
        static::assertThat($shortcode, new LogicalNot(new ShortcodeExists(static::getAllShortcodes(), $message)));
    }

    protected static function getAllShortcodes(): array
    {
        global $shortcode_tags;

        return (array) $shortcode_tags;
    }

    /**
     * @param string $shortcode
     *
     * @return callable|null Array, Object or other callable but NULL when not found.
     */
    protected static function getShortcodeCallback(string $shortcode)
    {
        $all = static::getAllShortcodes();

        return $all[$shortcode] ?? null;
    }

    /**
     * @param string $shortcodeName
     *
     * @return Expectation
     */
    public function mockShortcode(string $shortcodeName)
    {
    	if (empty($GLOBALS['shortcode_tags'])) {
			$GLOBALS['shortcode_tags'] = [];
		}

    	return $this->wpIntApply(new Shortcode($shortcodeName, $GLOBALS['shortcode_tags']));
    }
}
