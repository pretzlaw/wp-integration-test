<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * WidgetUsesClass.php
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

namespace Pretzlaw\WPInt\Constraint\Widget;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * WidgetUsesClass
 *
 * @copyright  2020 M. Pretzlaw (https://rmp-up.de)
 */
class WidgetIsInstanceOf extends Constraint
{
    /**
     * @var string
     */
    private $baseId;
    /**
     * @var string
     */
    private $classOrInterface;

    public function __construct(string $idBase, string $className)
    {
        parent::__construct();

        $this->baseId = $idBase;
        $this->classOrInterface = $className;
    }

    protected function failureDescription($other): string
    {
        return sprintf('widget "%s"', $this->baseId) . ' ' . $this->toString();
    }

    protected function matches($other): bool
    {
        foreach ($other as $item) {
            if ($item->id_base !== $this->baseId) {
                continue;
            }

            $itemClass = get_class($item);

            // Keeping them seperated to see if all are covered in test coverage.

            if ($itemClass === $this->classOrInterface) {
                return true;
            }

            if (in_array($this->classOrInterface, (array) class_parents($itemClass), true)) {
                return true;
            }

            if (in_array($this->classOrInterface, (array) class_implements($itemClass), true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return 'is instance of ' . $this->classOrInterface;
    }


}
