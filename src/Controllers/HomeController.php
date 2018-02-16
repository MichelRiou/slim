<?php
namespace m2i\slim\controllers;    // pensez a charger l'autoload   via composer.json et composer dump-autoload
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    public function index(RequestInterface $request,ResponseInterface $response){
        $response->getBody()->write("it works");
    }
}