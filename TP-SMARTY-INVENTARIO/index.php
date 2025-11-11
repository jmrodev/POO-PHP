<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Let the built-in PHP server serve existing static files (css, js, images)
// so requests for resources under the document root are not routed through this script.
if (php_sapi_name() === 'cli-server') {
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . $urlPath;
    if (is_file($file)) {
        // Return false to let the built-in web server serve the requested resource
        return false;
    }
}

require_once 'router.php';
