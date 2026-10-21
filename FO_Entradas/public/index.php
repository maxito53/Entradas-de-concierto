<?php 

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

define("__ROOT__", __DIR__ . "/../");

try {
    include_once '../classes/Core/Autoload.php';
    $autoload = new Autoload(__ROOT__ . "classes/");
    $autoload->register();
    
    FrontController::dispatch();    
} catch (Exception $e) {
    $error = new ErrorView();
    $error->show($e);
}