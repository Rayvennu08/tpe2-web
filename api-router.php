<?php
require_once './libs/router.php';
require_once './app/controllers/game-api.controller.php';

// crea el router
$router = new Router();

// defina la tabla de ruteo
$router->addRoute('games', 'GET', 'GameApiController', 'getGames');
$router->addRoute('games/:ID', 'GET', 'GameApiController', 'getGame');
$router->addRoute('games/:ID', 'DELETE', 'GameApiController', 'deleteGame');
$router->addRoute('games', 'POST', 'GameApiController', 'insertGame'); 

// ejecuta la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);