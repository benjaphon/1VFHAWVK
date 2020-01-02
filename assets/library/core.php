<?php

function base_url() {
    return "http://localhost/playitnow";
}

function base_path() {
    return $_SERVER['DOCUMENT_ROOT'] . "/playitnow";
}

function salt_pass($pass) {
    return md5("borbaimaisoft" . $pass);
}
