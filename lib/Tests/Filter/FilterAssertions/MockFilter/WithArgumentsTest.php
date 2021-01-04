<?php

namespace Pretzlaw\WPInt\Tests\Filter\FilterAssertions\MockFilter;


use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\FilterAssertions;

class WithArgumentsTest extends AbstractTestCase {
	use FilterAssertions;

	/**
	 * Matching different input/signatures.
	 *
	 * This is not as handy as it seems except for one simple trick.
	 * If you can define the order in which the signatures come in then it is possible to fetch different signatures
	 * like this:
	 *
	 * ```
	 * $this->mockFilter( $name )->expects( $this->at(0) )->with( 'c' )->willReturn( 1337 );
	 * $this->mockFilter( $name )->expects( $this->at(1) )->with( 'a', 'b' )->willReturn( 42 );
	 * ```
	 */
	public function testMatchesDifferentSignatures() {
        $name = md5( __METHOD__ );

        $this->mockFilter( $name )->expects( $this->at(3) )->with( 'c' )->willReturn( 1337 );
        $this->mockFilter( $name )->expects( $this->at(0) )->with( 'a', 'b' )->willReturn( 42 );
        $this->mockFilter( $name )->expects( $this->at(1) )->with( 'a', 'z' )->willReturn( 13 );

        static::assertEquals( 42, apply_filters( $name, 'a', 'b' ) );
        static::assertEquals( 13, apply_filters( $name, 'a', 'z' ) );
        static::assertEquals( 'gg', apply_filters( $name, 'gg' ) );
        static::assertEquals( 1337, apply_filters( $name, 'c' ) );
	}
}
