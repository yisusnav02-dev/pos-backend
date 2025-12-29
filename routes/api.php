<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// Cargar todas las rutas desde modules/*/Routes/api.php
$modulePath = base_path('modules');

foreach (File::directories($modulePath) as $moduleDir) {
    $routeFile = $moduleDir . '/Routes/api.php';
    if (File::exists($routeFile)) {
        require $routeFile;
    }
}
