<?php

declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src\PsalmError;

class PsalmResult {
	public int $total;
	public int $totalTaintedPlugins;
	public int $totalNoTaint;
	private array $results;

	public function addResult( PsalmPluginResult $result ): void {
		$this->results[] = $result;
	}

	public function getResults(): array {
		return $this->results;
	}
}
