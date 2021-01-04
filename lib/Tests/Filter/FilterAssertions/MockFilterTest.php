<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions;


use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Filter\FilterAssertions;
use Pretzlaw\WPInt\Tests\AbstractTestCase;

class MockFilterTest extends AbstractTestCase
{
    use FilterAssertions;

    public function testThrowsExceptionWhenExpectationNotMatched()
    {
        $filterName = uniqid('', true);
        /** @var  $mock */
        $mock = $this->mockFilter($filterName);
        $mock->expects($this->exactly(2));

        apply_filters($filterName, true);

        try {
            $mock->__phpunit_verify();
        } catch (ExpectationFailedException $e) {
            // Clean up
            $mock->addFilter();
            apply_filters($filterName, true);


            static::assertContains(
                'Filter was expected to be applied 2 times, actually applied 1 times.',
                $e->getMessage()
            );

            return;
        }

        throw new AssertionFailedError('Verfiy did not throw an exception as expected.');
    }
}
