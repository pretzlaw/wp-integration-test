<?php


namespace Pretzlaw\WPInt\Traits;


trait PluginAssertions {
	/**
	 * @param $plugin
	 *
	 * @throws \PHPUnit\Framework\AssertionFailedError
	 */
	protected static function assertPluginIsActive( $plugin ) {
		$activePlugin = '';

		foreach ( wp_get_active_and_valid_plugins() as $activePlugin ) {
			if ( plugin_basename( $activePlugin ) === $plugin ) {
				break;
			}
		}

		static::assertEquals(
			plugin_basename( $activePlugin ),
			$plugin,
			sprintf( 'Plugin "%s" is not active', $plugin )
		);
	}
}
