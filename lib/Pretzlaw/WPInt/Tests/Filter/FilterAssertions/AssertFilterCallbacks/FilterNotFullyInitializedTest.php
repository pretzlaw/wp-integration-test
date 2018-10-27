<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks;


use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Tests\AllTraits;

/**
 * Class FilterNotFullyInitializedTest
 * @package Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks
 * @backupGlobals
 * @inheritdoc
 */
class FilterNotFullyInitializedTest extends AbstractTestCase
{
    /**
     * @var string
     */
    private $targetFilter;

    protected function setUp()
    {
        parent::setUp();

        global $wp_filter;

        $this->targetFilter = uniqid('', true);
        $wp_filter[$this->targetFilter] = null;
    }

    /**
     * assertFilterNotHasCallback
     *
     * It could happen that there is no such filter present.
     * In that case the assertion for not having a callback would pass too.
     *
     */
    public function testAssertFilterNotHasCallback()
    {
        static::assertNull(AllTraits::assertFilterNotHasCallback($this->targetFilter, ''));
    }
}