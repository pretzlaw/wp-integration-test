<?php

namespace Pretzlaw\WPInt\Tests\Functions;


use function Pretzlaw\WPInt\prepare_env;
use Pretzlaw\WPInt\Tests\AbstractTestCase;

/**
 * prepare_env()
 *
 * WordPress presumes a specific environment when it runs.
 * This is not given in the shell and would lead to errors and notices.
 * So we need to scaffold a proper but simple environment for WordPress.
 *
 * @package Pretzlaw\WPInt\Tests\Functions
 * @backupGlobals enabled
 */
class PrepareEnvTest extends AbstractTestCase {
	/**
	 * @group unit
	 */
	public function testDoesNotOverwriteExistingServerVars() {
		$expected = [
			'SERVER_PROTOCOL' => uniqid( '', true ),
			'HTTP_USER_AGENT' => uniqid( '', true ),
			'REQUEST_METHOD'  => uniqid( '', true ),
			'REMOTE_ADDR'     => uniqid( '', true ),
		];

		$_SERVER = $expected;

		prepare_env();

		static::assertEquals( $expected['SERVER_PROTOCOL'], $_SERVER['SERVER_PROTOCOL'] );
		static::assertEquals( $expected['HTTP_USER_AGENT'], $_SERVER['HTTP_USER_AGENT'] );
		static::assertEquals( $expected['REQUEST_METHOD'], $_SERVER['REQUEST_METHOD'] );
		static::assertEquals( $expected['REMOTE_ADDR'], $_SERVER['REMOTE_ADDR'] );

		static::assertCount( count( $expected ), $_SERVER, 'You missed some new preparations for $_SERVER' );
	}

	/**
	 * @group unit
	 */
	public function testItCreatesNonExistentSuperclass() {
		unset( $_SERVER );
		static::assertFalse( isset( $_SERVER ) );

		prepare_env();

		static::assertTrue( isset( $_SERVER ) );
	}
}