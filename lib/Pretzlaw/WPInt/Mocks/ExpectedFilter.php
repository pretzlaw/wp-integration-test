<?php


namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker as InvocationMockerBuilder;
use PHPUnit\Framework\MockObject\InvocationMocker;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;
use Pretzlaw\WPInt\FilterInvocation;

/**
 * @method InvocationMocker method( $constraint )
 */
class ExpectedFilter implements MockObject {
	/**
	 * @var array
	 */
	protected $args;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var bool
	 */
	private $return;

	/**
	 * @var \PHPUnit\Framework\TestCase
	 */
	private $testCase;

	/**
	 * @var InvocationMocker|null
	 */
	private $invocationMocker;

	public function __construct(
		string $name,
		$return = true,
		array $args = []
	) {
		$this->name     = $name;
		$this->return   = $return;
		$this->args     = array_values( $args );
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function __invoke() {
		return $this->__phpunit_getInvocationMocker()->invoke(
			new FilterInvocation( 'WordPress Filter ', $this->name, \func_get_args(), '', $this )
		);
	}

	/**
	 * @return bool
	 */
	public function __phpunit_hasMatchers() {
		return $this->__phpunit_getInvocationMocker()->hasMatchers();
	}

	/**
	 * @return InvocationMocker
	 */
	public function __phpunit_setOriginalObject( $originalObject ) {

	}

	/**
	 * Verifies that the current expectation is valid. If everything is OK the
	 * code should just return, if not it must throw an exception.
	 *
	 * @throws ExpectationFailedException
	 */
	public function __phpunit_verify() {
		$this->removeFilter();

		try {
			$this->__phpunit_getInvocationMocker()->verify();
		} catch ( ExpectationFailedException $e ) {
			throw new ExpectationFailedException( $this->fixExceptionMessage( $e ), $e->getComparisonFailure() );
		}
	}

    /**
     * Registers filter in WordPress.
     */
	public function addFilter() {
		\add_filter( $this->getName(), $this, 10, 10 );
	}

	/**
	 * Registers a new expectation in the mock object and returns the match
	 * object which can be infused with further details.
	 *
	 * @param Invocation $matcher
	 *
	 * @return InvocationMockerBuilder
	 */
	public function expects( Invocation $matcher ) {
		$invocationMocker = $this->__phpunit_getInvocationMocker()->expects( $matcher )->method( $this->name );

		// In case dev miss this we safely return the value that runs through the filter.
		$invocationMocker->willReturnArgument( 0 );

		return $invocationMocker;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function removeFilter() {
		return \remove_filter( $this->getName(), $this );
	}

	/**
	 * @return InvocationMocker
	 */
	public function __phpunit_getInvocationMocker() {
		if ( null === $this->invocationMocker ) {
			$this->invocationMocker = new \PHPUnit\Framework\MockObject\InvocationMocker( [ $this->name ] );
		}

		return $this->invocationMocker;
	}

	/**
	 * @param \Exception $e
	 *
	 * @return string
	 */
	protected function fixExceptionMessage( \Exception $e ) {
		return \strtr(
			$e->getMessage(),
			[
				'method name' => 'WordPress filter',
				' invoked '   => ' applied ',
				'Method '     => 'Filter ',
				' called '    => ' applied ',
			]
		);
	}
}
