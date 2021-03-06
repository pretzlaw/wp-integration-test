<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ShortcodeExists.php
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
 * @copyright  2021 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 */

namespace Pretzlaw\WPInt\Constraint\Shortcode;

/**
 * ShortcodeExists
 *
 * @copyright  2021 M. Pretzlaw (https://rmp-up.de)
 */
class ShortcodeExists extends AbstractShortcodeConstaint
{
    protected function matches($shortcodeName): bool
    {
        return (bool) $this->fetchShortcode((string) $shortcodeName);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return 'is registered as shortcode';
    }
}
