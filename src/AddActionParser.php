<?php

declare(strict_types=1);

namespace Tuncay\PsalmWpTaint\src;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;

class AddActionParser
{
    public array $found_expr = [];

    public array $actions_map = [];

    public function __construct()
    {

    }

    public function isAddAction(Expr $expr): bool
    {
        return $expr instanceof FuncCall && $expr->name == 'add_action';
    }

    public function parse(): void
    {
        foreach ($this->found_expr as $expr) {
            $args = $this->get_args($expr);
            if (!$this->actions_map[$args["hook"]]) {
                $this->actions_map[$args["hook"]] = array($args["callback"]);
            } else {
                $this->actions_map[$args["hook"]][] = $args["callback"];
            }
        }
    }

    private function get_args(Expr $expr): array
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