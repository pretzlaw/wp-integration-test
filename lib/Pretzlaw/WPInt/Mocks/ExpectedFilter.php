<?php


namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
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

	public function __invoke() {
		foreach ( \func_get_args() as $num => $value ) {
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

		$this->hasRun = true;

		return $this->return;
	}

	/**
	 * @return InvocationMocker
	 */
	public function __phpunit_getInvocationMocker() {

	}

	/**
	 * @return bool
	 */
	public function __phpunit_hasMatchers() {
		return true;
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

		if ( ! $this->hasRun() ) {
			throw new ExpectationFailedException( $this->getErrorMessage() );
		}
	}

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
	 * @return InvocationMocker
	 */
	public function expects( Invocation $matcher ) {
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
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function hasRun(): bool {
		return $this->hasRun;
	}

	/**
	 * @return bool
	 */
	public function removeFilter(): bool {
		return \remove_filter( $this->getName(), $this );
	}

	public function tearDown() {
		$this->removeFilter();
	}
}
