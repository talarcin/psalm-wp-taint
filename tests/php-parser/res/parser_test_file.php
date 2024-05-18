<?php


/**
 * @psalm-taint-sink html $value
 *
 * @param $value
 * @return mixed
 */
function test_sink($value): mixed
{
    return $value;
}

function init(): void
{
    filler('value');
}

function filler($key): mixed
{
    return test_sink($_POST[$key]);
}

init();