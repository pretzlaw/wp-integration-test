<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions;


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
 * @backupGlobals enabled
 */
class AssertFilterHasCallbackTest extends AbstractTestCase
{
    public function testFilterNotYetPresent()
    {
        // Empty is okay.
        AllTraits::assertFilterNotHasCallback('', '');
    }

    /**
     * assertFilterNotHasCallback
     *
     * When the assertion has been done to early in the WP runtime,
     * then it could happen that WordPress filters are not initialized yet.
     * In that case checking for non-existance of a filter will pass.
     */
    public function testTooEarly()
    {
        global $wp_filter;

        $wp_filter = null;

        static::assertNull(AllTraits::assertFilterNotHasCallback('', ''));
    }
}
