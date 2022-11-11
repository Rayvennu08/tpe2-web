<?php
require_once './libs/router.php';
require_once './app/controllers/game-api.controller.php';

// crea el router
$router = new Router();

// defina la tabla de ruteo
$router->addRoute('games', 'GET', 'GameApiController', 'getGames'); 
$router->addRoute('game/:ID', 'GET', 'GameApiController', 'getGame');
$router->addRoute('game/:ID', 'DELETE', 'GameApiController', 'deleteGame'); 
$router->addRoute('game', 'POST', 'GameApiController', 'insertGame'); 
$router->addRoute('filter/game', 'GET', 'GameApiController', 'filterByBrand');


// ejecuta la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);