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
 * @copyright  2020 Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @since      2020-01-09
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsInstanceOf;

/**
 * ShortcodeHasCallback
 *
 * @copyright  2020 Pretzlaw (https://rmp-up.de)
 */
class MatchesConstraint extends Constraint
{
    private $value;

    public function __construct($value)
    {
        parent::__construct();

        $this->value = $value;
    }

    protected function compare($expected, $actual): bool
    {
        if ($expected == $actual) {
            return true;
        }

        if ($expected instanceof Constraint) {
            return $expected->matches($actual);
        }

        if (is_object($expected) && is_object($actual)) {
            return spl_object_hash($expected) == spl_object_hash($actual);
        }

        if (!is_array($expected) || !is_array($actual)) {
            return false;
        }

        // Renumbering on both sides so that keys can be iterated and compared easier.
        $expected = $this->normalizeArray($expected);
        $actual = $this->normalizeArray($actual);

        foreach ($expected as $key => $item) {
            if (is_array($item)) {
                if (false === array_key_exists($key, $actual)) {
                    return false;
                }
            }

            if ($item instanceof Constraint && false === $item->matches($actual[$key])) {
                return false;
            }

            if (false === $this->compare($item, $actual[$key])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed|mixed[]|Constraint[] $constraint
     *
     * @return bool
     */
    protected function matches($constraint): bool
    {
        return $this->compare($constraint, $this->value);
    }

    /**
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