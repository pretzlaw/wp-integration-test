<?php

namespace Pretzlaw\WPInt\Tests;


use PHPUnit\Framework\TestCase;
use Pretzlaw\WPInt\Traits\ActionAssertions;
use Pretzlaw\WPInt\Traits\FilterAssertions;
use Pretzlaw\WPInt\Traits\FunctionsAssertions;
use Pretzlaw\WPInt\Traits\MetaDataAssertions;
use Pretzlaw\WPInt\Traits\PluginAssertions;
use Pretzlaw\WPInt\Traits\PostAssertions;
use Pretzlaw\WPInt\Traits\PostQueryAssertions;
use Pretzlaw\WPInt\Traits\PostTypeAssertions;
use Pretzlaw\WPInt\Traits\UserAssertions;

/**
 * Containing all tested traits.
 *
 * This also ensures that trait methods do not collide.
 * We also use the deprecated as long as they are there to keep up the support.
 *
 * @package Pretzlaw\WPInt\Tests
 */
class AllTraits extends TestCase {
	use ActionAssertions;
	use FunctionsAssertions;
	use FilterAssertions;
	use MetaDataAssertions;
	use PluginAssertions;
	use PostAssertions;
	// use PostQueryAssertions; this is in PostAssertions already
	use PostTypeAssertions;
	use UserAssertions;
}