<?php

namespace Tuncay\PsalmWpTaint\src;

use ArrayObject;

class PsalmError
{
    public string $errorType;
    public string $errorPath;
    public array $errorMessage;
}

class ErrorStmt
{
    public string $id;
    public string $stmt;
}