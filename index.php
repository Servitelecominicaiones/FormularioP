<?php
// require_once "models/users/db1.php";
require_once "models/DataBase.php";




    if (!isset($_REQUEST['c'])) {
        require_once "controller/Menu.php";
        $controller = new menu;
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
    
?>