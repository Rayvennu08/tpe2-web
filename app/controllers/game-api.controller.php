<?php
require_once './app/models/game.model.php';
require_once './app/views/api.view.php';
require_once './app/models/modelBrands.php';

class GameApiController {
    private $model;
    private $view;
    private $modelBrand;

    private $data;

    public function __construct() {
        $this->model = new GameModel();
        $this->view = new ApiView();
        $this->modelBrand = new brandsModel();

        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getBody() {
        $bodyString = file_get_contents("php://input");
        return json_decode($bodyString);
    }

    public function getGames() {

        /*DESARROLLO DE PAGINACION*/

        /*define("games_x_pagina", 10);
        $page = $_GET['page'];

        //Numero total de juegos
        $pages = $this->model->paginacion();

        $start = ($_GET['page']-1)*games_x_pagina;
        $Games = $this->model->getAllGames($start);
        $brands = $this->modelBrand->getAllBrands();*/



        //VALIDO si la tabla que llamo contiene algo, devuelve 0 si no tiene nada, y 1 si contiene algo.
        if(!empty($_GET['sort']) && !empty($_GET['order'])){
            $valueSort = $this->model->valueSort($_GET['sort']);
            if($_GET['order'] == "DESC"|| $_GET['order']  == "desc" || $_GET['order']  == "ASC"|| $_GET['order']  == "asc"){
                $games = $this->model->getAllGames($_GET['sort'], $_GET['order']);
            }
            else{
                return $this->view->response('El parametro requerido no existe.', 404);
            }
        }else if(!empty($_GET['order'])){
            if($_GET['order'] == "DESC"|| $_GET['order']  == "desc" || $_GET['order']  == "ASC"|| $_GET['order']  == "asc"){
                $games = $this->model->getAllGames($_GET['order']);
            }else{
                return $this->view->response('El parametro requerido no existe. Intente escribiendo asc o desc, o ASC o DESC.', 404);
            }
        }
        if(!empty($games)){
            $this->view->response($games);
        }else{
            $this->view->response('No existe el juego.', 404);
        }
    }


    public function getGame($params = null){
        //Obtengo el id del arreglo de params
        $id = $params[':ID'];
        $game = $this->model->get($id);

        //Si no existe, devuelvo 404
        if($game)
            $this->view->response($game, 200);
        else
            $this->view->response("El juego con el id=$id no existe", 404);
    }


    public function deleteGame($params = null){
        $id = $params[':ID'];

        $game = $this->model->get($id);
        if (!empty($game)){
            $this->view->response("Eliminado exitosamente.", 204);
            $this->model->delete($id);
        } else {
            $this->view->response("El juego con el id=$id no existe", 404);
        }
    }

    
    public function insertGame($params = null){
        /*
        Ejemplo de que es lo que debe enviarse en el raw de postman:
        {
            "juego_name": "bla bla",
            "sinopsis": "asdlnquwdnkasndajsnd232",
            "calificacion": 9,
            "id_brand": "asdq2"
        }*/

        $game = $this->getBody();
        if(empty($game->juego_name) || empty($game->sinopsis) || empty($game->calificacion)) {
            $this->view->response("Complete los datos", 404);
        } else {
            $nameBrand = $this->modelBrand->getBrandById($game->id_brand);
            if($nameBrand == true){
                $this->model->insert($game->juego_name, $game->sinopsis, $game->calificacion, $nameBrand->id_brand);
                $this->view->response($game, 201);
            }
            else{
                $this->view->response("$game->id_brand no existe en la base de datos", 404);
            }
        }
    }

    /*Funcion para filtrar items dependiendo del valor que tome la variable $table en la url de Postman*/
    public function filterByBrand() {
            $table = $_GET['tabla'];
            $filtro = $_GET['filtro'];

            /*
                Determino qué nombre toma $table para asignarle
                qué secciones de qué tabla debe buscar.
            */
            if($table == 'empresa'){
                $table = 'brand_name';
            }else if($table == 'juego'){
                $table = 'juego_name';
            }

            $filter = $this->model->filter($table, $filtro);
            
            if($filter){
                $this->view->response($filter);
            }
            else{
                $this->view->response("El filtro $filtro no existe.", 404);
            }

    }
}