<?php

function is_assoc(array $arr)
{
    if ($arr === []) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}