<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * CurrentPost.php
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
 * @copyright 2020 Pretzlaw
 * @license   https://rmp-up.de/license-generic.txt
 */

declare(strict_types=1);

namespace Pretzlaw\WPInt\Mocks\Post;

use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use Pretzlaw\WPInt\Mocks\AbstractMockObjectStack;
use Pretzlaw\WPInt\Mocks\BackupVariable;
use Pretzlaw\WPInt\Mocks\Php\ArrayMock;
use Pretzlaw\WPInt\Mocks\Recovery;
use RuntimeException;
use WP_Post;

/**
 * CurrentPost
 *
 * @copyright 2020 Pretzlaw (https://rmp-up.de)
 * @method InvocationMocker method($constraint)
 */
class CurrentPost extends AbstractMockObjectStack
{
    /**
     * @var WP_Post
     */
    private $post;

    /**
     * Copy of the ID to assure correct clean-up
     *
     * @var int
     */
    private $id;

    public function __construct(WP_Post $post)
    {
        if (empty($post->ID) || false === is_numeric($post->ID)) {
            throw new RuntimeException('You need to add an ID (integer) when mocking the current post');
        }

        $this->post = $post;

        parent::__construct([
            new BackupVariable($GLOBALS['post']),
            new Recovery(
                function () {
                    wp_cache_delete($this->id, 'posts');
                }
            )
        ]);
    }

    public function register()
    {
        $this->id = (int) $this->post->ID; // copy id for proper clean-up

        // @todo Should extend/use mockPost
        wp_cache_set( $this->id, $this->post, 'posts' );

        $GLOBALS['post'] = $this->post;
    }
}