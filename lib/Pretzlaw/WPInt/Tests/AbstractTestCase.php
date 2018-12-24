<?php

namespace Pretzlaw\WPInt\Tests;


use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase {
    /**
     * @var AllTraits
     */
    protected $traits;
    protected $backupGlobalsBlacklist = ['wpdb'];

    protected function setUp()
    {
        parent::setUp();

        $this->traits = new AllTraits();
    }
}