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
        if(!empty($games)){
            $games = $this->model->getAllGames();
            return $games;
        }
        //VALIDO si la tabla que llamo contiene algo, devuelve 0 si no tiene nada, y 1 si contiene algo.
        if(!empty($_GET['filtro']) && !empty($_GET['sort'])&&!empty($_GET['order'])){
            $this->filterByBrand();
        }else if(!empty($_GET['filtro']) && !empty($_GET['sort'])){
            $this->filterByBrand();
        }else if(!empty($_GET['filtro']) && !empty($_GET['order'])){
            $this->filterByBrand();
        }else if(!empty($_GET['filtro'])){
            $this->filterByBrand();
        }else if(!empty($_GET['sort']) && !empty($_GET['order'])){
            $this->orderSort();
        }else if (!empty($_GET['order'])){
            $this->orderSort();
        }else if(!empty($_GET['sort'])){
            $this->orderSort();
        }else{
            $games = $this->model->getAllGames();
            if(empty($games) || !$games){
                $this->view->response('URL ingresada incorrecta.', 404);
            }else{
                $this->view->response($games);
            }
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
            "id_brand": "Electronic Arts" (Debe ser una empresa existente en la base de datos)
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

    /*Funcion para filtrar items dependiendo del valor que tome la variable $sort en la url de Postman*/
    public function filterByBrand() {
        if(!empty($_GET['sort'])){
            $sort = $_GET['sort'];
            $valueSort = $this->model->valueSort($sort);
        }
        if(!empty($_GET['filtro'])){
            $filtro = $_GET['filtro'];
        }
        if(!empty($_GET['order'])){
            $order = $_GET['order'];
        }

            /*
                Determino qué funcionalidad se dará dependiendo del orden
                o uso que se haga de los distintos parámetros; 'sort', 'filtro' u 'order'.
            */
        if(!empty($sort) && !empty($filtro) && !empty($order)){
            $games = $this->model->filter($sort, $filtro, $order);
        }else if(!empty($sort) && !empty($filtro)){
                if($valueSort != 0){
                    $games = $this->model->filter($sort, $filtro, null);
                }else{
                    return $this->view->response('El parametro de sort '.$sort.' requerido no existe.', 404);
                }
            }else if(!empty($order) && !empty($filtro)){
                $games = $this->model->filter("juego_name" , $filtro, $order);
            }else if(!empty($filtro)){
                $games = $this->model->filter("juego_name", $filtro, null);
            }
            if($games){
                return $this->view->response($games);
            }else{
                return $this->view->response("El filtro $filtro no existe.", 404);
            }

    }

    public function orderSort(){
        if(!empty($_GET['sort']) && !empty($_GET['order'])){
            $valueSort = $this->model->valueSort($_GET['sort']);
            if($valueSort != 0){
                if($_GET['order'] == "DESC"|| $_GET['order']  == "desc" || $_GET['order']  == "ASC"|| $_GET['order']  == "asc"){
                    $games = $this->model->getAllGames($_GET['sort'], $_GET['order']);
                }
                else{
                    return $this->view->response('El parametro requerido de order no existe. Intente escribiendo asc o desc, o ASC o DESC.', 404);
                }
            }else{
                $Sort = $_GET["sort"];
                return $this->view->response('El parametro '.$Sort.' requerido no existe.', 404);
            }
        }else if(!empty($_GET['order'])){
            if($_GET['order'] == "DESC"|| $_GET['order']  == "desc" || $_GET['order']  == "ASC"|| $_GET['order']  == "asc"){
                $games = $this->model->getAllGames($_GET['order']);
            }else{
                return $this->view->response('El parametro requerido no existe. Intente escribiendo asc o desc, o ASC o DESC.', 404);
            }
        }else if(!empty($_GET['sort'])){
            $valueSort = $this->model->valueSort($_GET['sort']);
            if($valueSort != 0){
                $games = $this->model->getAllGames($_GET['sort']);
            }else{
                $Sort = $_GET["sort"];
                return $this->view->response('El parametro '.$Sort.' requerido no existe.', 404);
            }
        }
        if(!empty($games)){
            $this->view->response($games);
        }else{
            $this->view->response($games);
        }
    }
}