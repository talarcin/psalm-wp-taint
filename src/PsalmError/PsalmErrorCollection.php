<?php

declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src\PsalmError;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @author Tuncay Alarcin
 */
class PsalmErrorCollection implements IteratorAggregate, ArrayAccess, Countable {
	protected array $psalmErrors;

	public function __construct() {
		$this->psalmErrors = [];
	}

	public function getIterator(): Traversable {
		return new \ArrayIterator( $this->psalmErrors );
	}

	public function offsetExists( mixed $offset ): bool {
		$this->checkOffsetType( $offset );

		return isset( $this->psalmErrors[ $offset ] );
	}

	public function offsetGet( mixed $offset ): mixed {
		$this->checkOffsetType( $offset );

		return $this->psalmErrors[ $offset ];
	}

	public function offsetSet( mixed $offset, mixed $value ): void {
		if ( ! is_a( $value, PsalmError::class ) ) {
			throw new \UnexpectedValueException();
		}
		if ( is_null( $offset ) ) {
			$this->psalmErrors[] = $value;
		} else {
			$this->checkOffsetType( $offset );
			$this->psalmErrors[ $offset ] = $value;
		}
	}

	public function offsetUnset( mixed $offset ): void {
		$this->checkOffsetType( $offset );
		unset( $this->psalmErrors[ $offset ] );
	}

	public function count(): int {
		return count( $this->psalmErrors );
	}

	private function checkOffsetType( mixed $offset ): void {
		if ( ! is_int( $offset ) ) {
			throw new \UnexpectedValueException( "Offset must be an integer" );
		}
	}
}
