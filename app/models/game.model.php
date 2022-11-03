<?php

class TaskModel {

    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=listajuegos;charset=utf8', 'root', '');
    }

    /**
     * Devuelve la lista de juegos completa.
     */
    public function getAllGames() {
        
        // Conexion con db ya abierta por el constructor de la clase

        // Ejecuto la sentencia (2 subpasos)
        $query = $this->db->prepare("SELECT * FROM games");
        $query->execute();

        // Obtengo los resultados
        $games = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de objetos
        
        return $games;
    }

    public function get($id){
        $query = $this->db->prepare("SELECT * FROM games WHERE id_juego = ?");
        $query->execute([$id]);
        $game = $query->fetch(PDO::FETCH_OBJ);

        return $game;
    }

    //Elimina un juego por su id de la base de datos
    public function delete($id){
        $query = $this->db->prepare("DELETE FROM games WHERE id_juego = ?");
        $query->execute([$id]);
    }

    //Inserta un juego en la base de datos
    public function insert($title, $sinopsis, $qualification, $id){
        $query = $this->db->prepare("INSERT INTO games (juego_name, sinopsis, calificacion, id_brand) VALUES (?, ?, ?, ?)");
        $query->execute(array($title, $sinopsis, $qualification, $id));

        return $this->db->lastInsertId();
    }
}