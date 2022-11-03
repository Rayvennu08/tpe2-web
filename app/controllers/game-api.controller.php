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

}