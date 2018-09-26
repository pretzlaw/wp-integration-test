# WordPress Integration Test Helper

> Mocking return value of functions/filters and more for testing WordPress with PHPUnit.

Writing tests with WordPress is a pain as
[the official WordPress Unit Tests](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)
always require a lot of hands on for custom projects
and other UnitTest-Frameworks need to mock the hell out of WordPress because ... well, WordPress.
This project aims for having a set of helper that ...

- ... can be integrated in your already existing tests (using Traits).
- ... ease testing down to the common PHPUnit style.
- ... allows you can test your package against other Plugins or Themes.

Overall the goal is **simplicity** and **no time wasting crap** (for me and you).

## Install

Download or just

    composer install --dev pretzlaw/wp-integration-test

We do not require that much:

- PHP 7.0
- phpUnit 6
- WordPress 4

Tests expand continuously to cover a bigger range one day. 

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

*Hint: If you write tests and want to have a customer-readable test evidence to give away
then you may want to have a look at the
[PHPUnit Test- and Documentation-Generator](https://github.com/pretzlaw/phpunit-docgen).*

The bootstrapping just loads WordPress
[as the wp-cli would do](https://github.com/wp-cli/wp-cli/blob/master/php/wp-cli.php)
using the `\Pretzlaw\WP_Int\run_wp()` function.


### Examples

Mock that a post exists:

```php
class FooTest extends \PHPUnit\Framework\TestCase {
    use \Pretzlaw\WPInt\Traits\PostQueryAssertions;
    
    protected function setUp() {
        static::mockGetPost(
            1337,
            new \WP_Post(
                (object) [
                    'post_type'    => 'page',
                    'ID'           => 1337,
                    'post_content' => 'foobar',
                ]
            )
        );
    }
    
    funciton testBar() {
        $post = get_post(1337);
    }
}
```

Or its meta data via `use \Pretzlaw\WPInt\Traits\MetaDataAssertions`:

```php
static::mockPostMeta(
    'some_meta_key',
    [
        'think_of' => 'any value you like',
    ],
    1337 // optional: remove the ID if all objects shall have the above meta value
);
```

Or other things like:

- Specific filter
- Specific actions
- Disable `wp_die`

... and more.


## Support and Migration

This is simply a list of releases and their EOL:

:grey_question: | Version   | Supported until   | Extended security support
-----------     | --------- | ----------------  | --------------------------
:no_entry_sign: | 0.1.0     | -                 | -


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
