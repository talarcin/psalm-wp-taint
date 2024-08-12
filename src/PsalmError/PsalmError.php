<?php

declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src\PsalmError;

/**
 * @author Tuncay Alarcin
 */
class PsalmError {
	public string $errorType;
	public string $errorPath;
	public array $errorMessage;

	public function __construct( string $type = "", string $path = "" ) {
		$this->errorType    = $type;
		$this->errorPath    = $path;
		$this->errorMessage = [];
	}

	/**
	 * Compares if given PsalmError has same field values as current.
	 *
	 * @param PsalmError $other
	 *
	 * @return bool
	 */
	public function equals( PsalmError $other ): bool {
		return $other->errorType == $this->errorType
		       && $other->errorPath == $this->errorPath
		       && $other->errorMessage == $this->errorMessage;
	}
}
