<?php

spl_autoload_register(function ($class) {
    $file = __DIR__ . '\\' . $class . '.php';
    if (file_exists($file)) {
        include $file;
    } else {
        exit('File not found. File: ' . $file);
    }
});
