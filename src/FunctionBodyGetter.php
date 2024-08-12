<?php

namespace Tuncay\PsalmWpTaint\src;

use PhpParser\Error;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

/**
 * @author Tuncay Alarcin
 */
class FunctionBodyGetter
{
    private array $functionStmts = [];
    private array $functionNames = [];

    public function __construct(array $actionsMap)
    {
        foreach ($actionsMap as $key => $callbacks) {
            $this->functionNames = array_merge($this->functionNames, $callbacks);
        }
    }

	/**
	 * Adds the given function statement to the list of function statements.
	 *
	 * @param Function_|ClassMethod $function
	 *
	 * @return void
	 */
    public function addFunctionStmt(Function_|ClassMethod $function): void
    {
        $this->functionStmts[] = $function;
    }

	/**
	 * Retrieves the list of function statements.
	 *
	 * @return array
	 */
    public function getFunctionStmts(): array
    {
        return $this->functionStmts;
    }

	/**
	 * Retrieves the list of the found function names.
	 *
	 * @return array
	 */
    public function getFunctionNames(): array
    {
        return $this->functionNames;
    }

	/**
	 * Filters function bodies from file at given filepath matching the found function names.
	 *
	 * @param string $filepath
	 *
	 * @return bool
	 */
    public function filterMatchingFunctionBodiesFromFile(string $filepath): bool
    {
        $ast = $this->parseFile($filepath);

		if($ast == -1) return false;

        $traverser = new NodeTraverser();
        $visitor = new FunctionStmtNodeVisitor($this);
        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

		return true;
    }

	/**
	 * Writes found function statements to file at given path.
	 *
	 * @param string $filepath
	 *
	 * @return void
	 */
    public function writeFunctionStmtsToFile(string $filepath): void
    {
        $prettyPrinter = new Standard();
        file_put_contents($filepath, $prettyPrinter->prettyPrintFile($this->functionStmts));
    }

	/**
	 * Checks if given function name matches any of the found function names.
	 *
	 * @param string $functionName
	 *
	 * @return bool
	 */
    public function isMatchingFunctionName(string $functionName): bool
    {
        return in_array($functionName, $this->functionNames);
    }

    private function parseFile(string $filepath): array|int
    {
        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $code = file_get_contents($filepath);

        try {
            return $parser->parse($code);
        } catch (Error $error) {
            echo "Parse error in $filepath: {$error->getMessage()}\n";
            return -1;
        }

    }
}