<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions;


use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Tests\AllTraits;

class TruncateFilterTest extends AbstractTestCase
{
    private $filterName;

    protected function setUp()
    {
        parent::setUp();

        $this->filterName = uniqid('', true);
    }

    public function testTruncatesWpHook()
    {
        if (!class_exists('\\WP_Hook')) {
            $this->markTestSkipped('This old WP does not have WP_Hook');
        }

        add_filter($this->filterName, '__return_false');

        static::assertEquals(10, has_filter($this->filterName, '__return_false'));

        $this->traits->truncateFilter($this->filterName);

        static::assertFalse(has_filter($this->filterName, '__return_false'));
        static::assertFalse(has_filter($this->filterName));
    }

    public function testTruncatesArray()
    {
        if (!class_exists('\\WP_Hook')) {
            $this->markTestSkipped('This old WP does not have WP_Hook');
        }

        global $wp_filter;

        $wp_filter[$this->filterName] = [
            10 => [
                [
                    'function' => '__return_false',
                ]
            ]
        ];

        $this->traits->truncateFilter($this->filterName);

        static::assertEmpty($wp_filter[$this->filterName]);
    }

    public function testIgnoresInvalidFilters()
    {
        global $wp_filter;

        static::assertArrayNotHasKey($this->filterName, $wp_filter);

        $this->traits->truncateFilter($this->filterName);

        static::assertArrayNotHasKey($this->filterName, $wp_filter);
    }
}
