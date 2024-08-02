<?php

namespace Tuncay\PsalmWpTaint\src\PsalmError;

class PsalmPluginResult {

	private PsalmErrorArray $psalmErrors;

	public function __construct()
	{
		$this->psalmErrors = new PsalmErrorArray();
	}

	public function addError(PsalmError $error): void
	{
		$this->psalmErrors[] = $error;
	}

	public function equals(PsalmPluginResult $other): bool
	{
		if ($other->pluginSlug != $this->pluginSlug) return false;
		if (count($other->psalmErrors) != count($this->psalmErrors)) return false;

		for ($i = 0; $i < count($other->psalmErrors); $i++) {
			if (!$other->psalmErrors[$i]->equals($this->psalmErrors[$i])) {
				return false;
			}
		}

		return true;
	}
}