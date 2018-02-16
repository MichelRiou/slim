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
    "/hello/{firstName}/{name}/{month:[a-z]{3,}}-{year:\d{4}}",                       // ce qui est entre / et - est considéré comme {month}
    function (Request $request, Response $response, array $args) {
        $firstName = $args["firstName"];
        $name = $args["name"];
        $year = $args["year"];
        $month = $args["month"];
        if ($year > 2000) {
            return $response->withRedirect("/redirect");
        } else {
            // $name=$request->getParam("name" ?? "world");
            return $response->getBody()->write("Hello $firstName $name
        vous êtes né en $year au mois de $month");
        }
    })->setName("hello");
$app->get(
    "/redirect", function (Request $request, Response $response) {
    $response->getBody()->write("vous avez été redirigé");
}
);
$app->get(
    "/home", function(Request $resquest, Response $response){
    $url=$this->get("router")->pathFor("hello",
        ["firstName"=>"Tycho",
        "name"=>"Brahé",
        "month"=>"janvier",
        "year"=>"1520"]
        );
    $link="<a href=$url>lien vers hello</a>";
    $response->getBody()->write($link);
}
);
$app->get("/products/all",
    function (Request $request, Response $response){     // La function est le controleur qui récupère la route et la formatte
        $data = [
            ["code"=> "A5", "price"=> 12],
["code"=> "B8", "price"=> 19]
];
return $response->withJson($data);
});

//Lancement du framwork
$app->run();