<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * TruncateFilter.php
 *
 * LICENSE: This source file is created by the company around M. Pretzlaw
 * located in Germany also known as rmp-up. All its contents are proprietary
 * and under german copyright law. Consider this file as closed source and/or
 * without the permission to reuse or modify its contents.
 * This license is available through the world-wide-web at the following URI:
 * https://rmp-up.de/license-generic.txt . If you did not receive a copy
 * of the license and are unable to obtain it through the web, please send a
 * note to mail@rmp-up.de so we can mail you a copy.
 *
 * @package   wp-integration-test
 * @copyright 2021 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Mocks\Filter;

use Pretzlaw\WPInt\ApplicableInterface;
use Pretzlaw\WPInt\CleanUpInterface;
use Pretzlaw\WPInt\Mocks\CannotBeAppliedMoreThanException;
use WP_Hook;
use const Pretzlaw\WPInt\Filter\FILTER_WAS_EMPTY;

/**
 * TruncateFilter
 *
 * @copyright 2021 Pretzlaw (https://rmp-up.de)
 */
class TruncateFilter implements CleanUpInterface, ApplicableInterface
{
    const FILTER_WAS_EMPTY = -1;
    private $backup;
    /**
     * @var array
     */
    private $filterNames;
    private $wpFilter;

    public function __construct(array $filterNames, &$wpFilter)
    {
        $this->filterNames = $filterNames;
        $this->wpFilter =& $wpFilter;
    }

    public function __invoke()
    {
        foreach ($this->backup as $filterName => $backup) {
            if (false === array_key_exists($filterName, $this->wpFilter)) {
                $backup = [$filterName => $backup];

                if (class_exists(WP_Hook::class)) {
                    $backup = WP_Hook::build_preinitialized_hooks($backup);
                }

                $this->wpFilter[$filterName] = $backup[$filterName];
                continue;
            }

            if (FILTER_WAS_EMPTY === $backup) {
                // Marked for deletion
                unset($this->wpFilter[$filterName]);
                continue;
            }

            if ($this->wpFilter[$filterName] instanceof WP_Hook) {
                $this->wpFilter[$filterName]->callbacks = $backup;
                continue;
            }

            // Old method
            $this->wpFilter[$filterName] = $backup;
        }
    }

    /**
     * @return callable The recovery method
     */
    public function apply()
    {
        if (null !== $this->backup) {
            // Already applied
            throw new CannotBeAppliedMoreThanException(__CLASS__, 1);
        }

        $this->backup = [];
        foreach ($this->filterNames as $filterName) {
            if (false === array_key_exists($filterName, $this->wpFilter)) {
                $this->backup[$filterName] = self::FILTER_WAS_EMPTY;

                continue;
            }

            $this->backup[$filterName] = $this->wpFilter[$filterName];
            if (class_exists('WP_Hook') && $this->wpFilter[$filterName] instanceof WP_Hook) {
                $this->backup[$filterName] = $this->backup[$filterName]->callbacks;
                $this->wpFilter[$filterName]->callbacks = [];

                continue;
            }

            $this->wpFilter[$filterName] = [];
        }

        return $this;
    }
}
