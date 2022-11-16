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
    }

    private function getBody() {
        // lee el body del request
        $bodyString = file_get_contents("php://input");
        return json_decode($bodyString);
    }

    public function getGames() {
        if(!empty($_GET['order'])){
            if($_GET['order'] == "DESC"|| $_GET['order']  == "desc" || $_GET['order']  == "ASC"|| $_GET['order']  == "asc"){
            }else{
                return $this->view->response("Puede que el parametro order este mal escrito, escribir DESC o desc, ASC o asc",400);
            }
        }
        if(!empty($games)){
            $games = $this->model->getAllGames();
            return $games;
        }
        //VALIDO si la tabla que llamo contiene algo, devuelve 0 si no tiene nada, y 1 si contiene algo.
        
        else if(!empty($_GET['filtro']) && !empty($_GET['sort'])&&!empty($_GET['order'])){
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
        }else if(isset($_GET['start']) && !empty($_GET['start']) && isset($_GET['products']) && !empty($_GET['products'])){
            $start = $_GET['start'];
            $products = $_GET['products'];

            /*
            Inicializo la variable $productsDB.
            El parametro $start controla el numero de pagina que se muestra
            y el parametro $products muestra la cantidad de juegos solicitada.
            */
            $productsDB = $this->model->getPagination($start, $products);
            
            /*
            * Determino una sentencia para cuando se solicite una pagina que
            * no contenga informacion.
            */
            if($productsDB == null){
                $this->view->response("La pagina solicitada no contiene informacion. Intente con un numero de pagina distinto.", 404);
            }else{
                $productsDB = $this->model->getPagination($start, $products);
                $this->view->response($productsDB);
            }
        }
        else{
            $games = $this->model->getAllGames();
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
            $this->view->response("Eliminado exitosamente.", 200);
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
            if($valueSort == 0){
                return $this->view->response("El parametro sort ingresado no existe.", 404);
            }
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
            if(!$games){
                return $this->view->response("El filtro $filtro no existe.", 404);
            }
        }
        else if(!empty($sort) && !empty($filtro)){
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
                return $this->view->response('El parametro '.$Sort.' ingresado no existe.', 404);
            }
        }else if(!empty($_GET['order'])){
            if($_GET['order'] == "DESC"|| $_GET['order']  == "desc" || $_GET['order']  == "ASC"|| $_GET['order']  == "asc"){
                $games = $this->model->getAllGames($_GET['order']);
            }else{
                return $this->view->response('El parametro order requerido no existe. Intente escribiendo asc o desc, o ASC o DESC.', 404);
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

    /*
     * Funcion que permite la paginacion de los datos, pasando por parametro, desde que pagina comenzar y la cantidad de productos.  
    */
    public function getPaginationForCountProducts($start, $products){
        $games = $this->model->getAllGames();
        if(isset($_GET['start']) && !empty($_GET['start']) && isset($_GET['products']) && !empty($_GET['products'])){
            if ((count($games) <= $start || $start < 0)) {
                $this->view->response("Error: ha ingresado un inicio superior al numero de registros o un valor de inicio negativo", 404);
            } else {
                $games = $this->model->getPagination($start, $products);
                $this->view->response($games);
            }
        }
    }
}