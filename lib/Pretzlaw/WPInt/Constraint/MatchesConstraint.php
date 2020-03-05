<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * ShortcodeHasCallback.php
 *
 * LICENSE: This source file is created by the company around Mike Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package    wp-integration-test
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @since      2020-01-09
 */

namespace Pretzlaw\WPInt\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsInstanceOf;

/**
 * ShortcodeHasCallback
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class MatchesConstraint extends Constraint
{
    protected $constraint;

    public function __construct($constraint)
    {
        parent::__construct();

        $this->constraint = $constraint;
    }

    protected function compare($actual, $constraint): bool
    {
        if ($constraint == $actual) {
            return true;
        }

        if ($constraint instanceof Constraint) {
            return $constraint->evaluate($actual, '', true);
        }

        if (is_object($constraint) && is_object($actual)) {
            return $constraint == $actual;
        }

        if (!is_array($constraint) || !is_array($actual)) {
            return false;
        }

        return $this->compareArray(
            $this->normalizeArray($actual),
            $this->normalizeArray($constraint)
        );
    }

    protected function compareArray($actual, $expected)
    {
        foreach ($expected as $key => $item) {
            if (is_array($item) && false === array_key_exists($key, $actual)) {
                return false;
            }

            if (false === $this->compare($actual[$key], $item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array|\Traversable $value
     *
     * @return bool
     */
    protected function matches($value): bool
    {
        return $this->compare($value, $this->constraint);
    }

    /**
     * Renumbering on both sides so that comparison is easier
     *
     * @param $value
     *
     * @return array
     */
    protected function normalizeArray(array $value): array
    {
        return array_merge(
            array_values(array_filter($value, 'is_int', ARRAY_FILTER_USE_KEY)),
            array_filter($value, 'is_string', ARRAY_FILTER_USE_KEY)
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return 'matches';
    }
}