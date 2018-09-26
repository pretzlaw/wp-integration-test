<?php

namespace Pretzlaw\WPInt;


use PHPUnit\Framework\MockObject\Invocation\ObjectInvocation;

class FilterInvocation extends ObjectInvocation {
	/**
	 * Generate return
	 *
	 * Return is generated when the invocation did not match.
	 * If so then we want to provide the given value (given to the apply_filter).
	 *
	 * @return mixed
	 * @throws \ReflectionException
	 */
	public function generateReturnValue() {
		if ( $this->getParameters() ) {
			$parameters = $this->getParameters();

			return reset( $parameters );
		}

		return parent::generateReturnValue();
	}

}