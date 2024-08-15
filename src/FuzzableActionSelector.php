<?php

declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src;

use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;

/**
 * @author Tuncay Alarcin
 */
class FuzzableActionSelector {
	private array $fuzzableActions;
	private array $addActionsMap;
	private PsalmResult $psalmResult;
	private array $interestingFilenames;
	private array $interestingLineNumbers;
	private array $interestingCallbackFunctions;

	public function __construct( array $addActionsMap, PsalmResult $psalmResult ) {
		$this->addActionsMap                = $addActionsMap;
		$this->psalmResult                  = $psalmResult;
		$this->fuzzableActions              = [];
		$this->interestingFilenames         = [];
		$this->interestingLineNumbers       = [];
		$this->interestingCallbackFunctions = [];
		$this->getInterestingFileNamesFromPsalmResult();
	}

	/**
	 * Retrieves interesting actions to fuzz from found interesting callbacks.
	 *
	 * @param string $dirPath
	 *
	 * @return bool
	 *
	 * Returns false, when given path is not a directory, and true if everything worked.
	 */
	public function selectActionsToFuzz( string $dirPath ): bool {
		if ( ! $this->scanFilesForFunctionNames( $dirPath ) ) {
			return false;
		}

		print_r( "Found filenames and lineno:\n" );
		print_r( $this->interestingFilenames );
		print_r( PHP_EOL );
		print_r( $this->interestingLineNumbers );
		print_r( $this->addActionsMap );

		foreach ( $this->addActionsMap as $action => $callbacks ) {

			foreach ( $callbacks as $callback ) {
				if ( in_array( $callback, $this->interestingCallbackFunctions ) ) {
					if ( in_array( $action, $this->fuzzableActions ) ) {
						continue;
					}
					$this->fuzzableActions[] = $action;
				}
			}
		}

		return true;
	}

	private function scanFilesForFunctionNames( string $dirPath ): bool {
		if ( ! is_dir( $dirPath ) ) {
			return false;
		}

		$phpFilesInTargetDir = Util::scanDirForPHPFiles( $dirPath );

		foreach ( $phpFilesInTargetDir as $filepath ) {
			$filename     = $this->getPHPFileNameFromPath( $filepath );
			$matchingKeys = array_keys( $this->interestingFilenames, $filename );

			if ( count( $matchingKeys ) == 0 ) {
				continue;
			}

			foreach ( $matchingKeys as $key ) {
				$startingIndex = $this->interestingLineNumbers[ $key ] - 1;
				$lines         = file( $filepath );
				$funcName      = "";

				for ( $i = $startingIndex; $i >= 0; $i -- ) {
					$trimmedLine = trim( $lines[ $i ] );
					if ( ! str_contains( $trimmedLine, "function" ) ) {
						continue;
					}

					$funcDeclarationParts = explode( " ", $trimmedLine );

					if ( $funcDeclarationParts[0] != "function" ) {
						$funcName = $funcDeclarationParts[2];
					} else {
						$funcName = $funcDeclarationParts[1];
					}

					$funcName                             = str_contains( $funcName, "(" ) ? explode( "(", $funcName )[0] : $funcName;
					$this->interestingCallbackFunctions[] = $funcName;

					break;
				}
			}
		}

		return true;
	}

	/**
	 * Writes actions to fuzz to a .json file at given filepath with given name.
	 *
	 * @param string $filename
	 *
	 * @return bool
	 */
	public function writeFuzzableActionsToFile( string $filepath ): bool {
		$fileContentAsJson = json_encode( $this->fuzzableActions );
		file_put_contents( $filepath . ".json", $fileContentAsJson );

		return true;
	}

	/**
	 * Retrieves the list of actions to fuzz.
	 *
	 * @return array
	 */
	public function getFuzzableActions(): array {
		return $this->fuzzableActions;
	}

	private function getInterestingFileNamesFromPsalmResult(): void {
		foreach ( $this->psalmResult->getResults() as $pluginResult ) {
			$pluginErrors = $pluginResult->psalmErrors;

			foreach ( $pluginErrors as $error ) {
				if ( $error->errorType != "TaintedHtml" ) {
					continue;
				}

				$tmp        = explode( "/", $error->errorMessage[1]["id"] );
				$tmp        = explode( ":", array_pop( $tmp ) );
				$filename   = $tmp[0];
				$lineNumber = $tmp[1];

				$this->interestingFilenames[]   = $filename;
				$this->interestingLineNumbers[] = intval( $lineNumber );
			}
		}
	}

	private function getPHPFileNameFromPath( string $filepath ): string|false {
		if ( ! str_ends_with( $filepath, ".php" ) ) {
			return false;
		}

		$tmp = explode( "/", $filepath );

		return array_pop( $tmp );
	}
}
