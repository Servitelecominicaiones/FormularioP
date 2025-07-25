<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "models/Database.php";

    if (!isset($_REQUEST['c'])) {
        require_once "controller/Menu.php";
        $controller = new Menu;
        $controller->main();
    } else {
        $controller = $_REQUEST['c'];
        require_once "controller/" . $controller . ".php";
        $controller = new $controller;
        $action = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'main';
        if (method_exists($controller, $action)) {
            // Llamar a la acción solicitada en el controlador
            $controller->$action();
        } else {
            // Acción no válida, manejar el error como desees
            echo "Error: Acción no válida.";
        }
    }
