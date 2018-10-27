<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\AssertFilterCallbacks;


use PHPUnit\Framework\AssertionFailedError;
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

    /**
     * FilterEmpty
     *
     * The filter could also exist but contain nothing,
     * neither a \WP_Hook object nor an array but it could be null.
     * In that case the filter is considered to be empty too.
     */
    public function testFilterEmptySucceeds()
    {
        static::assertNull(AllTraits::assertFilterEmpty($this->targetFilter));
    }

    public function testFilterNotEmptyFails()
    {
        $this->expectException(AssertionFailedError::class);
        AllTraits::assertFilterNotEmpty($this->targetFilter);
    }
}