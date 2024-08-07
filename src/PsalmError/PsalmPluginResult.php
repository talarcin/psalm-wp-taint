<?php
declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src\PsalmError;

class PsalmPluginResult {
	public string $pluginSlug;
	public int $count;
	public PsalmErrorCollection $psalmErrors;

	public function __construct( string $pluginSlug = "", int $count = 1 ) {
		$this->pluginSlug = $pluginSlug;
		$this->count = $count;
		$this->psalmErrors = new PsalmErrorCollection();
	}

	public function equals( PsalmPluginResult $other ): bool {
		return $this->pluginSlug === $other->pluginSlug
		       && $this->count === $other->count
		       && $this->psalmErrorsAreEqual( $other->psalmErrors );
	}

	private function psalmErrorsAreEqual( PsalmErrorCollection $other ): bool {
		for ( $i = 0; $i < count( $this->psalmErrors ); $i ++ ) {
			if ( ! $this->psalmErrors[ $i ]->equals( $other[ $i ] ) ) {
				return false;
			}
		}

		return true;
	}
}