<?php

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use PhpParser\Error;


final class ParserTests extends TestCase
{
    public function testFindPrevCallers()
    {
        $called = "filler";
        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $code = file_get_contents("res/parser_test_file.php");
        try {
            $ast = $parser->parse($code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return;
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CalledNodeVisitor($called));

        $traverser->traverse($ast);
    }
}

class CalledNodeVisitor extends NodeVisitorAbstract
{
    private string $called;

    public function __construct($called)
    {
        $this->called = $called;
    }

    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Expr\FuncCall || $node instanceof Node\Expr\MethodCall || $node instanceof Node\Expr\StaticCall) {
            if ($node->name->name == $this->called) {
            }
        }
    }

    private function getCallerName($line, $file): string
    {

    }
}