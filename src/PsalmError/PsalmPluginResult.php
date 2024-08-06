<?php
declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src\PsalmError;

class PsalmPluginResult {
	public string $pluginSlug;
	public int $count;
	public PsalmErrorCollection $psalmErrors;
}