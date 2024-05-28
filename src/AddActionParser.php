<?php

declare(strict_types=1);

namespace Tuncay\PsalmWpTaint\src;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;

class AddActionParser
{
    public array $foundExpressions = [];
    private array $actionsMap = [];

    public function __construct()
    {

    }

    /**
     * @param Expr $expr
     * @return bool
     */
    public function isAddAction(Expr $expr): bool
    {
        return $expr instanceof FuncCall && $expr->name == 'add_action';
    }

    public function addExpression(Expr $expr): void
    {
        if (!in_array($expr, $this->foundExpressions)) {
            $this->foundExpressions[] = $expr;
        }
    }

    /**
     * @return void
     */
    public function parseFoundExpressions(): void
    {
        foreach ($this->foundExpressions as $expr) {
            $args = $this->getArgs($expr);
            if (!array_key_exists($args["hook"], $this->actionsMap)) {
                $this->actionsMap[$args["hook"]] = array($args["callback"]);
            } else {
                $this->actionsMap[$args["hook"]][] = $args["callback"];
            }
        }

        $this->foundExpressions = [];
    }

    /**
     * @param string $filepath
     * @return void
     */
    public function writeActionsMapToFile(string $filepath): void
    {
        file_put_contents($filepath, json_encode($this->actionsMap));
    }

    /**
     * @param string $filepath
     * @return void
     */
    public function readActionsMapFromFile(string $filepath): void
    {
        if (!file_exists($filepath)) {
            return;
        }

        $file = file_get_contents($filepath);
        if ($file == null) {
            return;
        }

        $this->actionsMap = json_decode($file, true);
    }

    /**
     * @return array
     */
    public function getActionsMap(): array
    {
        return $this->actionsMap;
    }

    /**
     * @param Expr $expr
     * @return array
     */
    private function getArgs(Expr $expr): array
    {
        $args = array("hook" => "", "callback" => "");

        foreach ($expr->args as $arg) {
            if ($arg->value instanceof String_ && $args["hook"] == "") {
                $args["hook"] = $arg->value->value;
            } else if ($arg->value instanceof String_ && $args["callback"] == "") {
                $args["callback"] = $arg->value->value;
            } else if ($arg->value instanceof Array_) {
                $args["callback"] = $arg->value->items[1]->value->value;
            }
        }

        return $args;
    }

}