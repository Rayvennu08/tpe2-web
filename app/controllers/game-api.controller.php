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
        $this->model = new TaskModel();
        $this->view = new ApiView();
        $this->modelBrand = new brandsModel();

        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getBody() {
        $bodyString = file_get_contents("php://input");
        return json_decode($bodyString);
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function getGames($params = null) {
        $tasks = $this->model->getAllGames();
        $this->view->response($tasks);
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
}