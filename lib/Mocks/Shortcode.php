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
 * @package    wp-integration-test
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @since      2020-01-10
 */

namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker as InvocationMockerBuilder;
use PHPUnit\Framework\MockObject\Invocation\ObjectInvocation;
use PHPUnit\Framework\MockObject\InvocationMocker;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Shortcode
 *
 * @copyright 2020 M. Pretzlaw (https://rmp-up.de)
 */
class Shortcode
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function __invoke()
    {
        return $this->__phpunit_getInvocationMocker()->invoke(
            new ObjectInvocation('WordPress Shortcode ', $this->name, \func_get_args(), 'string', $this)
        );
    }

    /**
     * @param \Exception $e
     *
     * @return string
     */
    protected function fixExceptionMessage(\Exception $e)
    {
        return \strtr(
            $e->getMessage(),
            [
                'method name' => 'WordPress shortcode',
                'Method ' => 'Shortcode ',
                ' called ' => ' used ',
            ]
        );
    }

    public function register()
    {
        add_shortcode($this->name, $this);
    }

    protected function remove()
    {
        remove_shortcode($this->name);
    }
}
