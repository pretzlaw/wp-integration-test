<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains missing feature for PHPUnit
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
 * @package    pretzlaw/wp-integration-test
 * @copyright  2020 M. Pretzlaw
 * @license    https://rmp-up.de/license-generic.txt
 * @link       https://project.rmp-up.de/pretzlaw/wp-integration-test
 * @since      2018-12-27
 */

namespace Pretzlaw\WPInt;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Invocation\StaticInvocation;
use PHPUnit\Framework\MockObject\Stub;

/**
 * Different returns based on arguments
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-27
 */
class ArgumentSwitch
{
    private $default;
    private $rules = [];

    public function __construct($default)
    {
        $this->default = $default;
    }

    public function add($returnValue, array $arguments)
    {
        $this->rules[] = [
            'return' => $returnValue,
            'args' => $arguments
        ];
    }

    public function __invoke()
    {
        $id = $this->find(func_get_args());

        if (null !== $id) {
            return $this->rules[$id]['return'];
        }

        return $this->parseDefault(func_get_args());
    }

    private function parseDefault(array $func_get_args)
    {
        if ($this->default instanceof Stub) {
            return $this->default->invoke(new StaticInvocation(get_class($this), '__invoke', func_get_args(), 'mixed'));
        }

        return $this->default;
    }

    /**
     * @param array $arguments
     * @return int|string|null Position of the rule or NULL if not matched.
     */
    private function find(array $arguments)
    {
        $arguments = array_values($arguments);
        foreach ($this->rules as $ruleKey => $rule) {
            $matcher = array_values($rule['args']);

            foreach ($matcher as $key => $constraint) {
                if ($constraint instanceof Constraint) {
                    if (!$constraint->evaluate($arguments[$key], '', true)) {
                        // Mismatch: Continue with the next ruleset.
                        continue 2;
                    }

                    continue;
                }

                if ($constraint !== $arguments[$key]) {
                    // Continue with the next ruleset.
                    continue 2;
                }
            }

            // Passed all
            return $ruleKey;
        }

        return null;
    }
}