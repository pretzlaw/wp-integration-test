<?php

namespace Pretzlaw\WPInt\Tests;


use PHPUnit\Framework\TestCase;
use Pretzlaw\WPInt\Traits\WordPressTests;
use Pretzlaw\WPInt\WPAssert;

/**
 * Containing all tested traits.
 *
 * This also ensures that trait methods do not collide.
 * We also use the deprecated as long as they are there to keep up the support.
 *
 * @package Pretzlaw\WPInt\Tests
 *
 * @deprecated 1.0.0 Use WPAssert instead
 */
class AllTraits extends WPAssert {
}