<?php

declare(strict_types=1);

namespace Tuncay\PsalmWpTaint\src;

use PhpParser\Node\Expr;
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

    /**
     * @return void
     */
    public function parseFoundExpressions(): void
    {
        foreach ($this->foundExpressions as $expr) {
            $args = $this->getArgs($expr);
            if (!$this->actionsMap[$args["hook"]]) {
                $this->actionsMap[$args["hook"]] = array($args["callback"]);
            } else {
                $this->actionsMap[$args["hook"]][] = $args["callback"];
            }
        }
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
        $args = [];

        foreach ($expr->args as $arg) {

            if ($arg->value instanceof String_) {
                $args["hook"] = $arg->value->value;
            } else if ($arg->value instanceof Expr\Array_) {
                $args["callback"] = $arg->value->items[1]->value->value;
            }
        }

        return $args;
    }


}