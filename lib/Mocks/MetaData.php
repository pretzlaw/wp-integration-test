<?php

declare(strict_types=1);

namespace Pretzlaw\WPInt\Mocks;

use Mockery\Expectation;
use Mockery\ExpectationInterface;
use Mockery\HigherOrderMessage;
use Mockery\Matcher\AndAnyOtherArgs;
use Mockery\Matcher\Any;

class MetaData extends Filter
{
	/**
	 * @var null
	 */
	private $objectId;
	/**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $metaKey;

    /**
     * ExpectedMetaUpdate constructor.
     *
     * @param string $type
     * @param string $metaKey
     * @param null $objectId
     * @param null $single
	 *
     * @see \get_metadata() "get_{$meta_type}_metadata" filter.
     */
    public function __construct(
        string $type,
        $metaKey,
        $objectId = null
    )
    {
        $this->type = $type;
        $this->metaKey = $metaKey;

        if (null === $objectId) {
        	$objectId = new Any();
		}

		$this->objectId = $objectId;

		parent::__construct('get_' . $type . '_metadata', PHP_INT_MAX);
	}

	/**
	 * @return mixed|Expectation|ExpectationInterface|HigherOrderMessage
	 */
	public function apply()
	{
		return parent::apply()->with(
			new Any(),
			$this->objectId,
			$this->metaKey,
			// WP >= 5.5 has an additional arg after this (metaType)
			new AndAnyOtherArgs()
		);
	}
}
