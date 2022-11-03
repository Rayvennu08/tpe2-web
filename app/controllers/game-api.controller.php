<?php
require_once './app/models/game.model.php';
require_once './app/views/api.view.php';

class GameApiController {
    private $model;
    private $view;

    private $data;

    public function __construct() {
        $this->model = new TaskModel();
        $this->view = new ApiView();
        
        // lee el body del request
        $this->data = file_get_contents("php://input");
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
            $this->view->response($game);
        else
            $this->view->response("El juego con el id=$id no existe", 404);
    }

    public function deleteGame($params = null){
        $id = $params[':ID'];

        $game = $this->model->get($id);
        if($game){
            $this->model->delete($id);
            $this->view->response($game);
        } else {
            $this->view->response("El juego con el id=$id no existe", 404);
        }
    }

    public function insertGame($params = null){
        $game = $this->getData();

        if(empty($game->juego_name) || empty($game->sinopsis) || empty($game->calificacion)) {
            $this->view->response("Complete los datos", 404);
        } else {
            $id = $this->model->insert($game->juego_name, $game->sinopsis, $game->calificacion);
            $game = $this->model->get($id);
            $this->view->response($task, 201);
        }
    }
}