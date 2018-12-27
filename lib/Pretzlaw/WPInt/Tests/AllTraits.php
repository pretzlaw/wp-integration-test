<?php

namespace Pretzlaw\WPInt\Tests;


use PHPUnit\Framework\TestCase;
use Pretzlaw\WPInt\Traits\WordPressTests;

/**
 * Containing all tested traits.
 *
 * This also ensures that trait methods do not collide.
 * We also use the deprecated as long as they are there to keep up the support.
 *
 * @package Pretzlaw\WPInt\Tests
 */
class AllTraits extends TestCase {
	use WordPressTests;
}