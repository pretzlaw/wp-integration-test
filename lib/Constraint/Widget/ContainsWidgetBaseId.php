<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * WidgetExists.php
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
 * @since      2020-01-10
 */

namespace Pretzlaw\WPInt\Constraint\Widget;

use Pretzlaw\WPInt\Constraint\Constraint;

/**
 * WidgetExists
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class ContainsWidgetBaseId extends Constraint
{
    /**
     * @var string
     */
    private $baseId;

    public function __construct(string $idBase)
    {
        parent::__construct();

        $this->baseId = $idBase;
    }

    /**
     * @param \WP_Widget[]|\Traversable $other
     *
     * @return bool
     */
    protected function matches($other): bool
    {
        foreach ($other as $item) {
            if ($item->id_base === $this->baseId) {
                return true;
            }
        }

        return false;
    }

    public function toString(): string
    {
        return 'is registered';
    }

    protected function failureDescription($other): string
    {
        return sprintf('Widget with base-id "%s"', $this->baseId) . ' ' . $this->toString();
    }
}
