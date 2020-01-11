<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * BackupVariable.php
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

namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * BackupVariable
 *
 * @copyright 2020 Pretzlaw (https://rmp-up.de)
 * @method InvocationMocker method($constraint)
 */
class BackupVariable implements MockObject
{
    protected $backup;
    protected $invocationMocker;
    private $reference;

    public function __construct(&$variable)
    {
        $this->reference = &$variable;

        $this->backup($variable);
    }

    /**
     * @inheritDoc
     */
    public function __phpunit_getInvocationMocker()
    {
    }

    /**
     * @inheritDoc
     */
    public function __phpunit_hasMatchers(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function __phpunit_setOriginalObject($originalObject)
    {
    }

    public function __phpunit_verify(bool $unsetInvocationMocker = null)
    {
        if (null === $unsetInvocationMocker) {
            $unsetInvocationMocker = true;
        }

        if ($unsetInvocationMocker) {
            $this->reference = $this->backup;
        }
    }

    private function backup($variable)
    {
        $this->backup = $variable;
    }

    /**
     * @inheritDoc
     */
    public function expects(Invocation $matcher)
    {
    }

    public function __phpunit_setReturnValueGeneration(bool $returnValueGeneration)
    {
    }
}