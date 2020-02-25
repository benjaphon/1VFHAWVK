<?php

function base_url() {
    return "http://www.playitnow.net";
}

function base_path() {
    return $_SERVER['DOCUMENT_ROOT'];
}

function salt_pass($pass) {
    return md5("borbaimaisoft" . $pass);
}
