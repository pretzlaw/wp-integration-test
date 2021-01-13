<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AssocArrayHasSubset.php
 *
 * LICENSE: This source file is created by the company around M. Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package   wp-integration-test
 * @copyright 2021 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Constraint;

use ArrayObject;
use PHPUnit\Framework\ExpectationFailedException;
use Traversable;
use function array_replace_recursive;
use function iterator_to_array;

/**
 * AssocArrayHasSubset
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class AssocArrayHasSubset extends Constraint
{
	/**
	 * @var array
	 */
	private $checkedSubset;

	/**
	 * @var array|Traversable
	 */
	protected $subset;

	/**
	 * @var bool
	 */
	protected $strict;

	/**
	 * @param array|Traversable $subset
	 * @param bool               $strict Check for object identity
	 */
	public function __construct($subset, $strict = false)
	{
		parent::__construct();

		$this->strict = $strict;
		$this->subset = $subset;
	}

	/**
	 * Evaluates the constraint for parameter $other
	 *
	 * If $returnResult is set to false (the default), an exception is thrown
	 * in case of a failure. null is returned otherwise.
	 *
	 * If $returnResult is true, the result of the evaluation is returned as
	 * a boolean value instead: true in case of success, false in case of a
	 * failure.
	 *
	 * @param mixed  $other        Value or object to evaluate.
	 * @param string $description  Additional information about the test
	 * @param bool   $returnResult Whether to return a result or throw an exception
	 *
	 * @return mixed
	 *
	 * @throws ExpectationFailedException
	 */
	public function matches($other): bool
	{
		//type cast $other & $this->subset as an array to allow
		//support in standard array functions.
		$other        = $this->toArray($other);
		$this->subset = $this->toArray($this->subset);

		$patched = array_replace_recursive($other, $this->subset);

		$this->checkedSubset = array_intersect_key($other, $this->subset);

		if ($this->strict) {
			return $other === $patched;
		}

		return $other == $patched;
	}

	/**
	 * Returns a string representation of the constraint.
	 *
	 * @return string
	 */
	public function toString(): string
	{
		return 'matches the subset ' . var_export($this->checkedSubset, true);
	}

	/**
	 * Returns the description of the failure
	 *
	 * The beginning of failure messages is "Failed asserting that" in most
	 * cases. This method should return the second part of that sentence.
	 *
	 * @param mixed $other Evaluated value or object.
	 *
	 * @return string
	 */
	protected function failureDescription($other): string
	{
		return 'post-type definition ' . $this->toString();
	}

	/**
	 * @param array|Traversable $other
	 *
	 * @return array
	 */
	private function toArray($other)
	{
		if ($other instanceof ArrayObject) {
			return $other->getArrayCopy();
		}

		if ($other instanceof Traversable) {
			return iterator_to_array($other);
		}

		// Keep BC even if we know that array would not be the expected one
		return (array) $other;
	}
}

