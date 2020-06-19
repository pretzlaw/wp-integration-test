<?php


namespace Pretzlaw\WPInt\Traits;


use Pretzlaw\WPInt\Mocks\ExpectedFilter;
use Pretzlaw\WPInt\Mocks\Php\ArrayMock;
use Pretzlaw\WPInt\Mocks\Post\CurrentPost;
use Pretzlaw\WPInt\Mocks\Post\ExpectWpInsertPost;
use Pretzlaw\WPInt\Mocks\Recovery;
use RuntimeException;
use WP_Post;

trait PostAssertions {
    /**
     * @var array
     * @deprecated 0.4.0 use properly set-up mocks instead
     */
	private $wpPostClutter = [];

    /**
     * @param array $thePost
     *
     * @return WP_Post
     */
    private function createWpPostObject($data): WP_Post
    {
        if (is_array($data)) {
            return new \WP_Post((object) $data);
        }

        return $data;
    }

	protected function expectWpPostCreationWithSubset( $expectedSubset ) {
        $mockObject = new ExpectWpInsertPost($expectedSubset);

        $mockObject->expects($this->atLeastOnce());
        $this->registerMockObject($mockObject);
        $this->wpPostClutter[] = $mockObject;

        $mockObject->addFilter();
	}

    /**
     * @after
     */
	protected function tearDownPostClutter() {
        foreach ($this->wpPostClutter as $clutter) {
            if ($clutter instanceof ExpectedFilter) {
                $clutter->removeFilter();
            }
        }
    }

    /**
     * @param WP_Post|array $post
     */
    protected function mockCurrentPost($post) {
        $mock = new CurrentPost($this->createWpPostObject($post));

        $this->registerMockObject($mock);
        $mock->register();
    }

    protected function mockPost(int $id, $thePost)
    {
        $returnVal = $this->createWpPostObject($thePost);

        if (!$returnVal->ID) {
            $returnVal->ID = $id;
        }

        wp_cache_set( $id, $returnVal, 'posts' );

        if (isset($this)) {
            $this->registerMockObject(new Recovery(
                static function () use ($id) {
                    wp_cache_delete($id, 'posts');
                }
            ));
        }
    }

    /**
     * @param int $id
     * @param     $returnVal
     * @deprecated 0.4.0 Use ::mockPost instead
     */
    protected static function mockGetPost(int $id, $returnVal)
    {
        if ( \is_array( $returnVal ) ) {
            $returnVal = new \WP_Post( new \ArrayObject( $returnVal ) );
        }

        if (!$returnVal->ID) {
            $returnVal->ID = $id;
        }

        wp_cache_set( $id, $returnVal, 'posts' );
    }
}
