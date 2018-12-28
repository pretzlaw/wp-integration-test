<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Testing for ActionAssertions
 *
 * LICENSE: This source file is created by the company around Mike Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://mike-pretzlaw.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@mike-pretzlaw.de so we can mail you a copy.
 *
 * @package    pretzlaw/wp-integration-test
 * @copyright  2018 Mike Pretzlaw
 * @license    https://mike-pretzlaw.de/license-generic.txt
 * @link       https://project.mike-pretzlaw.de/pretzlaw/wp-integration-test
 * @since      2018-12-27
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Tests\Actions;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\IsType;
use Pretzlaw\WPInt\Constraint\ActionEmpty;
use Pretzlaw\WPInt\Constraint\ActionHasCallback;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\ActionAssertions;

/**
 * Actions
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-27
 */
class ActionNotEmptyTest extends AbstractTestCase
{
    use ActionAssertions;

    private $actionName;
    private $actionCallback;
    /**
     * @var ActionHasCallback
     */
    private $actionConstraint;
    private $emptyActionName;

    protected function setUp()
    {
        parent::setUp();

        $this->actionName = 'my-own-action';
        $this->actionCallback = '__return_true';
        $this->actionConstraint = new ActionEmpty();

        add_action($this->actionName, $this->actionCallback);
    }

    public function testSucceedsForRegisteredCallbacks()
    {
        static::assertNotFalse(has_action($this->actionName, $this->actionCallback));
        static::assertFalse($this->actionConstraint->evaluate($this->actionName, '', true));
        static::assertActionNotEmpty($this->actionName);
    }

    public function testFailsForMissingActions()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertActionNotEmpty(uniqid('', true));
    }

    public function testFailsForEmptyActions()
    {
        // If you use WP correctly then there is no such thing as empty arrays/hooks.
        $this->testFailsForMissingActions();
    }

    protected function tearDown()
    {
        remove_all_actions($this->actionName);

        parent::tearDown();
    }
}