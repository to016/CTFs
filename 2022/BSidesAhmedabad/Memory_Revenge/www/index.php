<?php
include 'common.php';
include 'filter.php';
include 'model.php';

foreach ($_FILES as $_key => $_value) {
    foreach ($keyarr as $k) {
        if (!isset($_FILES[$_key][$k])) {
            exit("CMS Error: Request Error!");
        }
    }
    if (preg_match('#^(cfg_|GLOBALS)#', $_key)) {
        exit('Request var not allow for uploadsafe!');
    }
    $$_key = $_FILES[$_key]['tmp_name'];
    ${$_key . '_name'} = $_FILES[$_key]['name'];
    ${$_key . '_type'} = $_FILES[$_key]['type'] = preg_replace('#[^0-9a-z\./]#i', '', $_FILES[$_key]['type']);
    ${$_key . '_size'} = $_FILES[$_key]['size'] = preg_replace('#[^0-9]#', '', $_FILES[$_key]['size']);

    if (empty(${$_key . '_size'})) {
        ${$_key . '_size'} = @filesize($$_key);
    }
}

if(isset($_GET['chance'])){
    if(strlen($_GET['chance']) > 9 | filter($_GET['chance'], 1) | checkLetterNums($_GET['chance'])) exit("No Hack T.T");
    eval($_GET['chance']);
}