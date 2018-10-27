<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks;


use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsAnything;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Tests\AllTraits;

/**
 * Class FilterDoesNotExistTest
 * @package Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks
 * @backupGlobals
 */
class FilterDoesNotExistTest extends AbstractTestCase
{
    protected function setUp()
    {
        parent::setUp();

        global $wp_filter;
        $wp_filter = [];
    }

    public function testFilterNotHasCallbackSucceeds()
    {
        static::assertNull(AllTraits::assertFilterNotHasCallback(uniqid('', true), new IsAnything()));
    }

    public function testFilterHasCallbackFails()
    {
        $this->expectException(AssertionFailedError::class);

        AllTraits::assertFilterHasCallback(uniqid('', true), new IsAnything());
    }
}