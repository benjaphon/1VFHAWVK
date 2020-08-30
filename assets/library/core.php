<?php

function base_url() {
    return APP_BASE_URL;
}

function base_path() {
    return APP_BASE_PATH;
}

function salt_pass($pass) {
    return md5("borbaimaisoft" . $pass);
}
