<?php
require_once __DIR__ . '/polyfills.php';

/**
 * Register the autoloader for the Facebook SDK classes.
 */
spl_autoload_register(function ($class) {
    // Check if the class is in the Facebook namespace
    if (strpos($class, 'Facebook\\') !== 0) {
        return;
    }

    // Get the relative class name
    $class = str_replace('\\', '/', substr($class, 9));

    // Get the absolute file path
    $file = __DIR__ . '/' . $class . '.php';

    // Load the class file
    if (file_exists($file)) {
        require $file;
    }
});