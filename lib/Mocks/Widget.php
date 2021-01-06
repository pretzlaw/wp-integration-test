<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Widget.php
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
 * @since      2020-01-11
 */

namespace Pretzlaw\WPInt\Mocks;

use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Widget
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 * @method InvocationMocker method($constraint)
 */
class Widget
{
    private $target;

    /**
     * Widget constructor.
     *
     * @param \WP_Widget|MockObject $widgetOrMock
     */
    public function __construct($widgetOrMock)
    {
        $this->target = $widgetOrMock;

        parent::__construct($widgetOrMock->id_base);
    }

    public function register()
    {
        register_widget($this->target);
    }

    protected function remove()
    {
        unregister_widget($this->target);
    }
}
