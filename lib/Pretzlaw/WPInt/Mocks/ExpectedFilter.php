<?php


namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker as InvocationMockerBuilder;
use PHPUnit\Framework\MockObject\Invocation\ObjectInvocation;
use PHPUnit\Framework\MockObject\InvocationMocker;
use PHPUnit\Framework\MockObject\Matcher\AnyParameters;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;
use Pretzlaw\WPInt\ClutterInterface;

/**
 * @method InvocationMocker method( $constraint )
 */
class ExpectedFilter implements MockObject, ClutterInterface {
	/**
	 * @var array
	 */
	protected $args;

	/**
	 * @var bool
	 */
	private $hasRun = false;

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
		\PHPUnit\Framework\TestCase $testCase,
		string $name,
		$return = true,
		array $args = []
	) {
		$this->testCase = $testCase;
		$this->name     = $name;
		$this->return   = $return;
		$this->args     = array_values( $args );
	}

	public function __call( $name, $arguments ) {
		// TODO: Implement @method InvocationMocker method($constraint)
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function __invoke() {
		$returnValue = $this->__phpunit_getInvocationMocker()->invoke(
			new ObjectInvocation( 'WordPress Filter ', $this->name, \func_get_args(), '', $this )
		);

		$this->validateParameters( \func_get_args() );

		/// @deprecated This should be determined using `atLeastOnce` and similar mock methods.
		$this->hasRun = true;

		return $returnValue;
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
	 * @deprecated 1.0.0 Why is this here? Move this to constructor
	 */
	public function addFilter() {
		\add_filter( $this->getName(), $this, 10, 10 );

		$this->testCase->registerMockObject( $this );
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

	public function getArgs() {
		return $this->args;
	}

	public function getErrorMessage() {
		$message = sprintf( 'Filter "%s" did not run', $this->getName() );

		if ( $this->getArgs() ) {
			$message .= ' with arguments:';
			$message .= PHP_EOL . '- ' . implode( PHP_EOL . '- ', $this->getArgs() );
		}

		return $message;
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
	public function hasRun() {
		return $this->hasRun;
	}

	/**
	 * @return bool
	 */
	public function removeFilter() {
		return \remove_filter( $this->getName(), $this );
	}

	public function tearDown() {
		$this->removeFilter();
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
	 * @return ExpectationFailedException
	 */
	private function fixExceptionMessage( \Exception $e ) {
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

	/**
	 * @deprecated This should be done using proper mock methods like `withAnyParameter` etc.
	 *
	 * @param $parameters
	 */
	private function validateParameters( $parameters ) {
		foreach ( $parameters as $num => $value ) {
			if ( ! array_key_exists( $num, $this->args ) ) {
				break;
			}

			$expectedValue = $this->args[ $num ];

			if ( $expectedValue instanceof AnyParameters ) {
				continue;
			}

			if ( false === $expectedValue instanceof Constraint ) {
				$expectedValue = new IsEqual( $expectedValue );
			}

			$expectedValue->evaluate( $value );
		}
	}
}
