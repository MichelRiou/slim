<?php
//index.php dans le dossier public
//Auto chargement des classes
require dirname(__DIR__) . "/vendor/autoload.php";
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


//Instanciation du framework
$app = new App();

//Définition d'une route
$app->get(
    "/hello",
    function (Request $request, Response $response) {
        return $response->getBody()->write("Hello");
    });

//Lancement du framwork
$app->run();