<?php

function testFunctionOne(): int
{
    $someVar = 5;
    return $someVar;
}
function testFunctionTwo(int $value): int
{
    $someVar = 5;
    return $someVar - $value;
}