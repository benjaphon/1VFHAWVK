<?php

function base_url() {
    return "http://borbaimai.thddns.net:5530/1VFHAWVK";
}

function base_path() {
    return $_SERVER['DOCUMENT_ROOT'] . "/1VFHAWVK";
}

function salt_pass($pass) {
    return md5("borbaimaisoft" . $pass);
}
