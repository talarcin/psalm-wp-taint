<?php
declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src\PsalmError;

class PsalmError {
	public string $errorType;
	public string $errorPath;
	public array $errorMessage;

	public function __construct() {
		$this->errorType    = '';
		$this->errorPath    = '';
		$this->errorMessage = [];
	}

	public function equals( PsalmError $other ): bool {
		return $other->errorType == $this->errorType
		       && $other->errorPath == $this->errorPath
		       && $other->errorMessage == $this->errorMessage;
	}
}
