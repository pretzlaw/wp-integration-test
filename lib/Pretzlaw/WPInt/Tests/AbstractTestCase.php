<?php

namespace Pretzlaw\WPInt\Tests;

use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;
use Pretzlaw\WPInt\Constraint\FilterHasCallback;
use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Tests\Mocks\MockFilter;

abstract class AbstractTestCase extends TestCase {
    /**
     * @var AllTraits
     */
    protected $traits;
    protected $backupGlobalsBlacklist = ['wpdb'];

    protected function setUp()
    {
        parent::setUp();

        $this->traits = new AllTraits();
    }

    protected function assertUnregisterFilterAfterTest(string $filter, ExpectedFilter $mock, string $message = '')
    {
        $hasFilter = new FilterHasCallback($filter);
        $hasNotFilter = new LogicalNot($hasFilter);

        $hasNotFilter->evaluate($mock, 'Filter exists already before adding it.');

        $mock->addFilter();

        $hasFilter->evaluate($mock, 'Filter has not been registered');

        try {
            $mock->__phpunit_verify();
        } catch (\Exception $e) {
            // Whatever, we are testing other things here.
        }

        static::assertThat($mock, $hasNotFilter, $message);
    }
}