# WordPress Integration Test Helper

> Mocking return value of functions/filters and more for testing WordPress with PHPUnit.

Writing tests with WordPress is a pain as the very old
[the official WordPress Unit Tests](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)
always require a lot of hands on for custom projects
and other Testing-Frameworks try to mock the hell out of WordPress.
The solution is to have a nice integration tests package that ...

- ... can be integrated into your already existing tests (using Traits).
- ... enabled you to test your package against other Plugins or Themes.
- ... ease testing down to the common PHPUnit style.

Overall the goal is **simplicity** and **no time wasting crap** (for me and you).

## Install

Download or just

    composer install --dev pretzlaw/wp-integration-test

We do not require that much
([see Packagist.org for more details](https://packagist.org/packages/pretzlaw/wp-integration-test)):

- PHP 7.0 - 7.3
- phpUnit 6.5 - 7.5
- WordPress 4.9 - 5.3

Tests expand continuously to cover a bigger range one day
([see Travis CI](https://travis-ci.org/pretzlaw/wp-integration-test)).


## Usage

Either you have some of these or you need basic bootstrapping
in the phpunit.xml or phpunit.dist.xml like this:

```xml
<phpunit bootstrap="vendor/Pretzlaw/WPInt/bootstrap.php">
	<testsuites>
		<testsuite name="default">
		    <!-- CHANGE THIS TO WHERE YOUR PHPUNIT TEST CLASSES ARE -->
			<directory>lib/tests</directory>
		</testsuite>
	</testsuites>
</phpunit>
```

The bootstrapping just loads WordPress
[as the wp-cli would do](https://github.com/wp-cli/wp-cli/blob/master/php/wp-cli.php)
using the `\Pretzlaw\WP_Int\run_wp()` function.


*Hint: If you write tests and want to have a customer-readable test evidence
then you may want to have a look at the
[PHPUnit Test- and Documentation-Generator](https://github.com/pretzlaw/phpunit-docgen).*


### Examples

If you know PHPUnit already then this speaks for itself:

```php
class FooTest extends \PHPUnit\Framework\TestCase {

    use \Pretzlaw\WPInt\Traits\WordPressTests;
    // or use the WPAssert::assert...() methods 
    
    function testBar() {
        // Simple assertions
        $this->assertActionHasCallback( 'init', 'my_own_init' );
        $this->assertPostTypeArgs( 'my-own', [ 'public' => false ] );
        
        // Assertions using constraints
        $this->assertShortcodeHasCallback( [ new IsInstanceOf( MyOwn::class ), 'some_method' ], 'my_shortcode_here' );
        
        // Mock posts or meta-data
        $this->mockGetPost( 1337, [ 'post_content' => 'foobar' ] );
        $this->mockPostMeta( 'some_key', 'Some value!' ); // For all posts
        $this->mockMetaData( 'my-own-cpt', 'another_key', 'ec', 1337 ); // Just for ID 1337
        
        // Expect/mock actions, filter or the cache
        $this->mockAction( 'my_own_action' )->expects( $this->once() );
        $this->mockFilter( 'user_has_cap' )
             ->expects( $this->any() )
             ->willReturn( true );
        // $this->mockCache()->...
        
        // Or make use of the shortcuts
        $this->mockCacheGet( 'my-own-cache', 'yeah!' );
        $this->disableWpDie();
        
        // After all this is still PHPUnit
        static::assertTrue( my_own_plugin_foo_getter_thingy() );
    }
}
```

Feel free to request for additional features or point out more common shortcuts
by [opening an issue](https://github.com/pretzlaw/wp-integration-test/issues).


## License

Copyright 2020 Pretzlaw (rmp-up.de)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software
and associated documentation files (the "Software"), to deal in the Software without restriction,
including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies
or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE
AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
