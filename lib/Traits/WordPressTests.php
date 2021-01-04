<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Summary of multiple traits
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
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @link       https://project.rmp-up.de/pretzlaw/wp-integration-test
 * @since      2018-12-27
 */

namespace Pretzlaw\WPInt\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use Pretzlaw\WPInt\Mocks\PostCondition;
use RuntimeException;

/**
 * Simplify usage by gathering all traits in one alias
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-27
 */
trait WordPressTests
{
    /**
     * @var callable[]
     */
    protected $wpIntCleanUp = [];

    use ActionAssertions;
    use CacheAssertions;
    use \Pretzlaw\WPInt\Filter\FilterAssertions;
    use FunctionsAssertions;
    use MetaDataAssertions;
    use PluginAssertions;
    use PostAssertions;

    use PostTypeAssertions;
    use ShortcodeAssertions;
    use UserAssertions;
    use WidgetAssertions;

    /**
     * @after
     */
    public function wpIntCleanUp()
    {
        foreach ($this->wpIntCleanUp as $callback) {
            $callback();
        }

        $this->wpIntCleanUp = [];
    }
}