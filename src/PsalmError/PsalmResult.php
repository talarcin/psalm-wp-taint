<?php

declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src\PsalmError;

/**
 * @author Tuncay Alarcin
 */
class PsalmResult {
	public int $total;
	public int $totalTaintedPlugins;
	public int $totalNoTaint;
	private array $results;

	public function __construct() {
		$this->total               = 0;
		$this->totalTaintedPlugins = 0;
		$this->totalNoTaint        = 0;
		$this->results             = [];
	}

	/**
	 * Adds given PsalmPluginResult to list of stored results.
	 *
	 * @param PsalmPluginResult $result
	 *
	 * @return void
	 */
	public function addResult( PsalmPluginResult $result ): void {
		$this->results[] = $result;
	}

	/**
	 * Returns stored PsalmPluginResults
	 *
	 * @return array
	 */
	public function getResults(): array {
		return $this->results;
	}

	/**
	 * Compares if given PsalmResults fields are equal to current PsalmResults.
	 *
	 * @param PsalmResult $other
	 *
	 * @return bool
	 */
	public function equals( PsalmResult $other ): bool {
		return $this->total === $other->total
		       && $this->totalTaintedPlugins === $other->totalTaintedPlugins
		       && $this->totalNoTaint === $other->totalNoTaint
		       && $this->pluginsResultsAreEqual( $other->results );
	}

	private function pluginsResultsAreEqual( array $otherPluginResults ): bool {
		for ( $i = 0; $i < count( $this->results ); ++ $i ) {
			if ( ! $this->results[ $i ]->equals( $otherPluginResults[ $i ] ) ) {
				return false;
			}
		}

		return true;
	}
}
