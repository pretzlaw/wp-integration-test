<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Tests for plugin assertions
 *
 * LICENSE: This source file is created by the company around Mike Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://mike-pretzlaw.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@mike-pretzlaw.de so we can mail you a copy.
 *
 * @package    pretzlaw/wp-integration-test
 * @copyright  2018 Mike Pretlaw
 * @license    https://mike-pretzlaw.de/license-generic.txt
 * @link       https://project.mike-pretzlaw.de/pretzlaw/wp-integration-test
 * @since      2018-12-25
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Plugins;

use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Constraint\PluginIsActive;
use Pretzlaw\WPInt\Filter\FilterAssertions;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\PluginAssertions;

/**
 * Check if plugin is active or not
 *
 * There are two functions for checking the state of a plugin:
 *
 *      $this->assertPluginIsActive( 'hello.php' );
 *      $this->assertPluginIsNotActive( 'hello.php' );
 *
 * The first one checks if the plugin has been enabled
 * or fails if it has not been disabled.
 * The second one checks if the plugin has been disabled
 * or fails if it has been enabled.
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-25
 */
class AssertPluginIsActiveTest extends AbstractTestCase
{
    use PluginAssertions;
    use FilterAssertions;

    protected function setUp()
    {
        parent::setUp();

        // Enable specific plugin via "active_plugins" option.
        $this->mockFilter('pre_option_active_plugins')->expects($this->atLeastOnce())->willReturn([
            'hello.php'
        ]);
    }

    public function testItFailsIfPluginIsNotActive()
    {
        $this->expectException(AssertionFailedError::class);
        $this->assertPluginIsActive('akismet/akismet.php');
    }

    public function testItSucceedsIfPluginIsActive()
    {
        $constraint = new PluginIsActive();

        static::assertTrue($constraint->evaluate('hello.php', '', true));
        $this->assertPluginIsActive('hello.php');
    }

    public function testItFailsIfPluginIsActive()
    {
        $this->expectException(AssertionFailedError::class);
        $this->assertPluginIsNotActive('hello.php');
    }

    public function testItSucceedsIfPluginIsNotActive()
    {
        $this->assertPluginIsNotActive('akismet/akismet.php');
    }
}