<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains class to check for active plugins.
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
 * @copyright  2020 Mike Pretlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @link       https://project.rmp-up.de/pretzlaw/wp-integration-test
 * @since      2018-12-25
 */

namespace Pretzlaw\WPInt\Constraint;

/**
 * Check if plugin is active
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-25
 */
class PluginIsActive extends Constraint
{
    /**
     * @var string[]
     */
    private $activePluginList;

    public function __construct($activePluginList = null)
    {
        parent::__construct();

        if (null === $activePluginList) {
            $activePluginList = wp_get_active_and_valid_plugins();
        }

        $this->activePluginList = $activePluginList;
    }

    protected function matches($other): bool
    {
        foreach ($this->activePluginList as $pluginSlug) {
            if (plugin_basename($pluginSlug) === $other) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return 'is active';
    }
}
