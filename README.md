![](https://img.shields.io/badge/PHP-7.0%20--%208.0-blue?style=for-the-badge&logo=php)
![](https://img.shields.io/badge/WordPress-4.6%20--%205.6-blue?style=for-the-badge&logo=wordpress)
![](https://img.shields.io/badge/PHPUnit-6.5%20--%209.5-blue?style=for-the-badge)

# WordPress Integration Test Helper

> Mocking return value of functions/filters and more for testing WordPress with PHPUnit.

Writing tests with WordPress is a pain as the very old
[official WordPress Unit Tests](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)
always require a lot of hands on for custom projects
and other Testing-Frameworks try to mock the hell out of WordPress.
The solution is to have a nice integration tests package that ...

- ... can be integrated into your already existing tests (using Traits).
- ... enabled you to test your package against other Plugins or Themes.
- ... allows testing in advanced complex projects.

Overall the goal is **simplicity** and **no time wasting crap** (for me and you).

## Install

Download or just

    composer install --dev pretzlaw/wp-integration-test

Besides PHP and WordPress we do not require that much
([see Packagist.org for more details](https://packagist.org/packages/pretzlaw/wp-integration-test)):

- phpUnit 6.5 - 9.5
- mockery 1.3


## Usage

If you start from scratch and do not have some bootstrapping already,
then you can use our bootstrapping like this:

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

Using this bootstrap.php is **not** mandatory.
Feel free to create a custom bootstrapping file,
when you need to test CLI and admin stuff too.


### Example

If you know PHPUnit already then asserting
and mocking shouldn't be something new.
With WPInt it can be for most PHPUnit Tests by adding one Trait:

```php
class FooTest extends \PHPUnit\Framework\TestCase {

    use \Pretzlaw\WPInt\Traits\WordPressTests;
    
    function testBar() {

        // Assertions (simple or with special constraints)
        static::assertActionHasCallback( 'init', 'my_own_init' );
        static::assertShortcodeHasCallback(
            [ new IsInstanceOf( MyOwn::class ), 'some_method' ],
            'my_shortcode'
        );
        
        // Mock posts or meta-data
        $this->mockGetPost( 1337 )->andReturn( /* your wp post mock */ );
        $this->mockPostMeta( 'some_key' )->andReturn( 'Some value!' ); // For all posts
        $this->mockMetaData( 'my-own-cpt', 'another_key', 1337 )->andReturn( 'ec' ); // Just for ID 1337
        
        // Mock actions, filter, cache, ...
        $this->mockFilter( 'user_has_cap' )
             ->andReturn( true );
        
        $this->mockCache()
            ->shouldReceive('get')
            ->with('my_own_cache')
            ->andReturn('yeah');

        // Or use one of several shortcuts and helper
        $this->disableWpDie();
    }
}
```

As you see above we are using
[mockery/mockery:~1](https://packagist.org/packages/mockery/mockery)
which is easier to use and maintain but uses
different method names (e.g. `shouldReceive`, `andReturn` as seen above).


### List of all Assertions

* assert Action Has Callback
* assert Action Not Empty
* assert Action Not Has Callback
* assert Filter Empty
* assert Filter Has Callback
* assert Filter Not Empty
* assert Filter Not Has Callback
* assert Plugin Is Active
* assert Plugin Is Not Active
* assert Post Type Args
* assert Post Type Labels
* assert Post Type Registered
* assert Shortcode Exists
* assert Shortcode Has Callback
* assert Shortcode Not Exists
* assert Widget Exists
* assert Widget Is Instance Of
* assert Widget Is Not Instance Of
* assert Widget Not Exists

### List of WordPress Expectations / Mocks

* expect Wp Post Insert Post
* mock Cache
* mock Current User
* mock Filter
* mock Get Post
* mock Meta Data
* mock Post Meta
* mock Shortcode
* mock User Meta


### Other testing helper

* backup Widgets
* disable `wp_die()`
* get All Shortcodes
* get Shortcode Callback
* get Widget Factory
* get Wp Hooks
* unregister All Widgets
* unregister Widgets By Id

Feel free to request for additional features
or point out more common shortcuts by
[opening an issue](https://github.com/pretzlaw/wp-integration-test/issues).


## License

Copyright 2021 Pretzlaw (rmp-up.de)

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
