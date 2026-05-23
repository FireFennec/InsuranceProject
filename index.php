<?php

    use Controller\DirectionController;
    use Model\Db;

    session_start();
    mb_internal_encoding("UTF-8");

    function autoloadFunction(string $class): void
    {
        $class = str_replace('\\', '/', $class);
        require $class . '.php';
    }
    spl_autoload_register("autoloadFunction");

    Db::connect('localhost', 'root', '', 'insurance');

    $directionController = new DirectionController();
    $directionController->process(array($_SERVER['REQUEST_URI']));

    $directionController->writeView();