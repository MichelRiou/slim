<?php
//index.php dans le dossier public
//Auto chargement des classes
define("ROOT_PATH", dirname(__DIR__));

require ROOT_PATH . "/vendor/autoload.php";
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use slim\Router;
use RedBeanPHP\R as R;

//Initialisation RedBean

R::setup("mysql:host=192.168.33.10;dbname=redbean;charset=utf8",
    "root", "123");

//Instanciation du framework   une clé Router est crée automatiquement
$app = new App([
    "settings" => [
        "displayErrorDetails" => "true"
    ]
]);

/*************
 *  Init Twig
 * ***********/

$container = $app->getContainer();
// Définition de la dépendance de Twig
$container["view"] = function ($container) {
    $viewEngine = new \Slim\Views\Twig(ROOT_PATH . "/src/views");
    /**
     * @var Router
     */
    $router = $container["router"];

    $viewEngine->addExtension(new \Slim\Views\TwigExtension(
            $router,
            "/"
        )
    );
    return $viewEngine;
};


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
    "/home", function (Request $resquest, Response $response) {
    $url = $this->get("router")->pathFor("hello",
        ["firstName" => "Tycho",
            "name" => "Brahé",
            "month" => "janvier",
            "year" => "1520"]
    );
    $link = "<a href=$url>lien vers hello</a>";
    $name = null;
    $products = ["legume", "fruits", "graines", "fleurs"];
    $books = R::findAll("books");
    return $this->get("view")->render($response, "home.html.twig",
        ["name" => $name, "products" => $products, "books" => $books]);
});
$app->get("/products/all",
    function (Request $request, Response $response) {     // La function est le controleur qui récupère la route et la formatte
        $data = [
            ["code" => "A5", "price" => 12],
            ["code" => "B8", "price" => 19]
        ];
        return $response->withJson($data);
    });
$app->get("/home/test", \m2i\slim\controllers\HomeController::class . ":index");

//Lancement du framwork
$app->run();