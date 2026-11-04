<?php

/**
 * Front controller for running the whole application from ONE folder inside
 * the web root (e.g. public_html/hydro -> https://example.com/hydro).
 *
 * The application code sits in this same folder, so the .htaccess next to
 * this file blocks every real file except index.php, build/ and uploads/.
 * Prefer the two-folder layout (code outside public_html) when the host allows it.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

// This folder is also the public directory (build/ and uploads/ live here).
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
