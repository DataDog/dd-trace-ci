<?php

header('Content-Type: application/json;charset=utf-8');

echo json_encode([
    'sapi' => PHP_SAPI,
    'php_version' => PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
    'ddtrace_installed' => extension_loaded('ddtrace'),
]);
