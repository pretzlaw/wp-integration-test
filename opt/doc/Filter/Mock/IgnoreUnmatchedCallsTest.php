<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AmongOtherCallsTest.php
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

namespace Pretzlaw\WPInt\Test\Filter\Mock;

use Pretzlaw\WPInt\Test\TestCase;
use Prophecy\Argument;

/**
 * Matching specific calls
 *
 * ```php
 * <?php
 *
 * $this->mockFilter($filterName)
 *      ->withArguments(Argument::exact(2))
 *      ->shouldBeCalledOnce()
 *      ->willReturn(42);
 * ```
 *
 * When making assertions on apply_filters then we want to make match only specific calls
 * and ignore all others.
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class IgnoreUnmatchedCallsTest extends TestCase
{
    public function testIgnoreOtherCalls()
    {
        $filterName = uniqid('', true);
        $this->mockFilter($filterName)
            ->withArguments([Argument::exact(2)])
            ->shouldBeCalledOnce()
            ->willReturn(42);

        static::assertSame(1, apply_filters($filterName, 1));
        static::assertSame(42, apply_filters($filterName, 2));
        static::assertSame(3, apply_filters($filterName, 3));
    }

//    public function testCanBeDescribedWithoutArguments()
//    {
//        $filterName = uniqid('', true);
//        $this->mockFilter($filterName)
//            ->willReturn(1337);
//
//        static::assertEquals(1337, apply_filters($filterName, 1));
//        static::assertEquals(1337, apply_filters($filterName, ''));
//        static::assertEquals(1337, apply_filters($filterName, 2));
//    }
}