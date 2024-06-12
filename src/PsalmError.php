<?php

namespace Tuncay\PsalmWpTaint\src;

use ArrayObject;

class PsalmError
{
    public string $errorType;
    public string $errorPath;
    public array $errorMessage;

    public function equals(PsalmError $error): bool
    {
        if ($error->errorType != $this->errorType) {
            return false;
        } else if ($error->errorPath != $this->errorPath) {
            return false;
        } else if ($error->errorMessage != $this->errorMessage) {
            return false;
        }
        
        return true;
    }
}

class ErrorStmt
{
    public string $id;
    public string $stmt;
}