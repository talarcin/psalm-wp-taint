<?php

namespace Tuncay\PsalmWpTaint\src;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitorAbstract;

class FunctionStmtNodeVisitor extends NodeVisitorAbstract
{
    private FunctionBodyGetter $functionBodyGetter;

    public function __construct(FunctionBodyGetter $functionBodyGetter)
    {
        $this->functionBodyGetter = $functionBodyGetter;
    }

    public function enterNode(Node $node): void
    {
        if (($node instanceof Function_ || $node instanceof ClassMethod) && $this->functionBodyGetter->isMatchingFunctionName($node->name)) {
            $this->functionBodyGetter->addFunctionStmt($node);
        }
    }

}