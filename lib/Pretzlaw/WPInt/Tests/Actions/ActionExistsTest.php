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
use PHPUnit\Framework\ExpectationFailedException;
use Pretzlaw\WPInt\Constraint\ActionHasCallback;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\ActionAssertions;

/**
 * Actions
 *
 * To check if an action exists in some way you can use the `assertActionHasCallback` method:
 *
 *      $this->assertActionHasCallback( 'init', 'my_own_init' );
 *      // or not
 *      $this->assertActionNotHasCallback( 'init', 'other_plugin_init' );
 *
 *
 * @copyright  2018 Mike Pretzlaw (https://mike-pretzlaw.de)
 * @since      2018-12-27
 */
class ActionExistsTest extends AbstractTestCase
{
    use ActionAssertions;

    private $actionName;
    private $actionCallback;
    /**
     * @var ActionHasCallback
     */
    private $actionConstraint;

    protected function setUp()
    {
        parent::setUp();

        $this->actionName = 'my-own-action';
        $this->actionCallback = '__return_true';
        $this->actionConstraint = new ActionHasCallback($this->actionName);

        add_action($this->actionName, $this->actionCallback);
    }

    public function testSucceedsForRegisteredCallbacks()
    {
        static::assertNotFalse(has_action($this->actionName, $this->actionCallback));
        static::assertTrue($this->actionConstraint->evaluate($this->actionCallback, '', true));
    }

    public function testFailsForMissingActions()
    {
        $this->expectException(AssertionFailedError::class);

        $this->actionConstraint->evaluate(uniqid('', true), $this->actionCallback);
    }

    public function testFailsForMissingCallbacks()
    {
        $this->expectException(AssertionFailedError::class);

        $this->actionConstraint->evaluate($this->actionName, uniqid('', true));
    }
}