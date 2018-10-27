<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions;


use PHPUnit\Framework\AssertionFailedError;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Tests\AllTraits;

/**
 * Check if callback has been registered or not
 *
 * WordPress allows to register custom functions / callbacks to certain filters.
 * Within your plugin, theme or application you may want to assert that your callback is still present.
 * This can be done
 *
 * @package Pretzlaw\WPInt\Tests\Filter\FilterAssertions
 *
 * @backupGlobals
 */
class AssertFilterHasCallbackTest extends AbstractTestCase
{
    public function testFilterNotYetPresent()
    {
        // Empty is okay.
        AllTraits::assertFilterNotHasCallback('', '');
    }

    /**
     * When the assertion has been done to early in the WP runtime,
     * then it could happen that WordPress filters are not initialized yet.
     *
     * @backupGlobals
     */
    public function testTooEarly()
    {
        global $wp_filter;

        $wp_filter = null;

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Filter have not yet been initialized');

        AllTraits::assertFilterNotHasCallback('', '');
    }

    public function testFilterNotPresent()
    {

    }
}