<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Overrides for wp_die()
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
 * @since      2018-12-24
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Functions;

use PHPUnit\Framework\Constraint\IsInstanceOf;
use Pretzlaw\WPInt\Filter\FilterAssertions;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\FunctionsAssertions;

/**
 * DisableWpDieTest
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-24
 */
class DisableWpDieTest extends AbstractTestCase
{
    use FunctionsAssertions;
    use FilterAssertions;

    public function testOverridesCommonHandler()
    {
        $this->disableWpDie();

        // Just once to be sure
        self::assertFilterHasCallback('wp_die_handler', new IsInstanceOf(ExpectedFilter::class));

        static::assertInternalType('int', apply_filters('wp_die_handler', ''));
    }

    public function testOverridesXmlrpcHandler()
    {
        $this->disableWpDie();

        static::assertInternalType('int', apply_filters('wp_die_xmlrpc_handler', ''));
    }

    public function testOverridesAjaxHandler()
    {
        $this->disableWpDie();

        static::assertInternalType('int', apply_filters('wp_die_ajax_handler', ''));
    }

    public function testWillBeRemovedAfterTest()
    {
        // See that WordPress acts as it should.
        self::assertFilterNotHasCallback('wp_die_handler', new IsInstanceOf(ExpectedFilter::class));
        self::assertInternalType('string', apply_filters('wp_die_ajax_handler', ''));
    }
}