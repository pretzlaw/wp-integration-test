<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * DisableWpDieTest.php
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

namespace Pretzlaw\WPInt\Test\Other;

use Pretzlaw\WPInt\Test\OtherTestCase;
use ReflectionFunction;

/**
 * DisableWpDieTest
 */
class DisableWpDieTest extends OtherTestCase
{
	protected function compatSetUp()
	{
		$this->disableWpDie();
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 */
	private function findAllAvailableFilter(string $code): array
	{
		$matches = [];
		preg_match_all('@apply_filters\s*\(\s*[\'"]([^\'",\)]+)@ui', $code, $matches);

		return array_unique(array_map('trim', $matches[1] ?? []));
	}

	/**
	 * @return \Generator
	 */
	public function getAllWpDieFilter()
	{
		foreach ($this->getWpDieFilter() as $item) {
			yield [$item];
		}
	}

	private function getCodeOfFunction(string $functionName)
	{
		$reflection = new ReflectionFunction($functionName);
		$code = file($reflection->getFileName());
		$startLine = $reflection->getStartLine() - 1;
		$code = array_slice($code, $startLine, $reflection->getEndLine() - $startLine);

		return implode(PHP_EOL, $code);
	}

	/**
	 * @covers \Pretzlaw\WPInt\Traits\FunctionsAssertions::getWpDieFilter()
	 */
	public function testMocksAllWpDieFilter()
	{
		$code = $this->getCodeOfFunction('wp_die');

		$allWpDieFilter = $this->findAllAvailableFilter($code);
		$coveredFilter = $this->getWpDieFilter();

		sort($allWpDieFilter);
		sort($coveredFilter);

		static::assertSame($allWpDieFilter, $coveredFilter);
	}

	/**
	 * @dataProvider getAllWpDieFilter
	 * @covers       \Pretzlaw\WPInt\Traits\FunctionsAssertions::disableWpDie()
	 */
	public function testRedirectsWpDieFilter(string $filterName)
	{
		static::assertEquals('time', apply_filters($filterName, ''));
	}
}
