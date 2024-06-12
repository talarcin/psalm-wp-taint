<?php

namespace Tuncay\PsalmWpTaint\src;

use ArrayIterator;

class PsalmPluginResult
{
    public string $pluginSlug;
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

class PsalmErrorArray extends ArrayIterator
{
    public function __construct(PsalmErrorArray ...$errors)
    {
        parent::__construct($errors);
    }
    public function current(): PsalmError
    {
        return parent::current();
    }
    public function offsetGet($offset): PsalmError
    {
        return parent::offsetGet($offset);
    }
}

class PsalmError
{
    public string $errorType;
    public string $errorPath;
    public array $errorMessage;

    public function equals(PsalmError $other): bool
    {
        $isEqual = $other->errorType == $this->errorType
            && $other->errorPath == $this->errorPath
            && $other->errorMessage == $this->errorMessage;

        return $isEqual;
    }
}

class ErrorStmt
{
    public string $id;
    public string $stmt;
}
