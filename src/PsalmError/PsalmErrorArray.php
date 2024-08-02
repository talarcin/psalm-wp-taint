<?php

namespace Tuncay\PsalmWpTaint\src\PsalmError;

use ArrayIterator;


class PsalmErrorArray extends ArrayIterator {
	public function __construct( PsalmErrorArray ...$errors ) {
		parent::__construct( $errors );
	}

	public function current(): PsalmError {
		return parent::current();
	}

	public function offsetGet( $key ): PsalmError {
		return parent::offsetGet( $key );
	}
}