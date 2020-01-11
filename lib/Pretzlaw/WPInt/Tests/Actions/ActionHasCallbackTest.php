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

namespace Pretzlaw\WPInt\Tests\Actions;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\IsType;
use Pretzlaw\WPInt\Constraint\ActionHasCallback;
use Pretzlaw\WPInt\Tests\AbstractTestCase;
use Pretzlaw\WPInt\Traits\ActionAssertions;

/**
 * Actions
 *
 * If you just don't want an action to be empty or have some specific callback
 * then this is the tool for you:
 *
 * To check if an action has a specific callback registered you can use the `assertActionHasCallback` method:
 *
 *      $this->assertActionHasCallback( 'init', 'my_own_init' );
 *      // or not
 *      $this->assertActionNotHasCallback( 'init', 'other_plugin_init' );
 *
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @since      2018-12-27
 */
class ActionHasCallbackTest extends AbstractTestCase
{
    use ActionAssertions;

    private $actionName;
    private $actionCallback;
    /**
     * @var ActionHasCallback
     */
    private $actionConstraint;

    public function getSucceedingConstraints(): array
    {
        return [
            [[new IsInstanceOf(__CLASS__), 'foo']],
            [[$this, new IsAnything()]],
            [[new IsAnything(), new IsAnything()]],
            [new IsType('array')]
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->actionName = 'my-own-action';
        $this->actionCallback = '__return_true';
        $this->actionConstraint = new ActionHasCallback($this->actionCallback, $this->actionName);

        add_action($this->actionName, $this->actionCallback);
    }

    public function testSucceedsForRegisteredCallbacks()
    {
        static::assertNotFalse(has_action($this->actionName, $this->actionCallback));
        static::assertTrue($this->actionConstraint->evaluate(static::getActionHooks(), '', true));
    }

    public function testFailsForMissingActions()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertActionHasCallback(uniqid('', true), $this->actionCallback);
    }

    public function testFailsForMissingCallbacks()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertActionHasCallback($this->actionName, uniqid('', true));
    }

    /**
     * @dataProvider getSucceedingConstraints
     * @param $constraint
     */
    public function testSucceedsWhenUsingConstraints($constraint)
    {
        $this->markTestSkipped('Needs custom comparator. GitHub issue 8');
        add_action($this->actionName, [$this, 'foo']);

        self::assertActionHasCallback($this->actionName, $constraint);
    }

    protected function tearDown()
    {
        remove_all_actions($this->actionName);

        parent::tearDown();
    }
}