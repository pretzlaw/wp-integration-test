<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * AbstractAssertionTestCase.php
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
 * @since      2020-01-11
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests;

use PHPUnit\Framework\AssertionFailedError;

/**
 * Assertion test case
 *
 * Usually you want to ...
 *
 * * test the assertion
 * * test that it raises an error when assertion is wrong
 * * change the message
 *
 * And have the negation of all this.
 * Following interface reminds about all those scenarios.
 *
 * Note: We use words like "validation" instead of "assert(ion)"
 * and "negation" instead of "assertNot" to not spill test things in the
 * auto-complete of the IDE.
 *
 * @copyright  2020 Pretzlaw (https://rmp-up.de)
 */
interface AssertionTestCase
{
    public static function testValidation();
    public function testValidationFailsWhenNotTrue();
    public function testValidationFailureMessageCanBeChanged();

    /**
     * LogicalNot part follows:
     */

    public function testNegation();
    public function testNegatingFailsWhenInvalid();
    public function testNegationFailureMessageCanBeChanged();
}