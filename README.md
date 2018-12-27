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

- PHP 7.0 - 7.2
- phpUnit 6
- WordPress 4.9 - 5.0

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
    
    function testBar() {
        // Simple assertions
        $this->assertActionHasCallback( 'init', 'my_own_init' );
        $this->assertPostTypeArgs( 'my-own', [ 'public' => false ] );
        
        // Mock a post or meta-data for any post-type
        $this->mockGetPost( 1337, [ 'post_content' => 'foobar' ] );
        $this->mockPostMeta( 'some_key', 'Some value!' ); // For all posts
        $this->mockMetaData( 'my-own-cpt', 'another_key', 'ec', 1337 ); // Just for ID 1337
        
        // Expect/mock actions and filter
        $this->mockAction( 'my_own_action' )->expects( $this->once() );
        $this->mockFilter( 'user_has_cap' )
             ->expects( $this->any() )
             ->willReturn( true );
        
        // Common shortcuts
        $this->disableWpDie();
        
        // After all this is still PHPUnit
        static::assertTrue( my_own_plugin_foo_getter_thingy() );
    }
}
```

Feel free to request for additional features or point out more common shortcuts
by [opening an issue](https://github.com/pretzlaw/wp-integration-test/issues).


## Support and Migration

This is simply a list of releases and their EOL:

:grey_question:    | Version   | Features until  | Hotfixes until
------------------ | --------- | --------------- | --------------
:warning:          | <= 0.1    | 2018-11-15      | 2018-02-28
:heavy_check_mark: |    0.2    | 2018-02-28      | 2018-03-31


## License

Copyright 2018 Mike Pretzlaw (mike-pretzlaw.de)

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
