<?php

class TaskModel {

    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=listajuegos;charset=utf8', 'root', '');
    }

    /**
     * Devuelve la lista de tareas completa.
     */
    public function getAllGames() {
        // 1. abro conexión a la DB
        // ya esta abierta por el constructor de la clase

        // 2. ejecuto la sentencia (2 subpasos)
        $query = $this->db->prepare("SELECT * FROM games");
        $query->execute();

        // 3. obtengo los resultados
        $games = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de objetos
        
        return $games;
    }
}