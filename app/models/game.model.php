<?php

class GameModel {

    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=listajuegos;charset=utf8', 'root', '');
    }

    /**
     * Devuelve la lista de juegos completa.
     */
    public function getAllGames($sort = null, $order = null) {

        // Conexion con db ya abierta por el constructor de la clase

            
        /*
        * Orden ASCENDENTE y DESCENDENTE por una columna de la tabla games.
        */
        if(!empty($sort) && !empty($order)){
            $query = $this->db->prepare("SELECT * FROM games ORDER BY $sort $order");
        }else if(!empty($sort)){
            if($sort == "DESC"|| $sort  == "desc" || $sort  == "ASC"|| $sort  == "asc"){            
                $query = $this->db->prepare("SELECT * FROM games ORDER BY id_juego $sort");
            }else{
                $query = $this->db->prepare("SELECT * FROM games ORDER BY $sort ASC");
            }
        }
        else{
            $query = $this->db->prepare("SELECT * FROM games");
        }

        $query->execute();

        // Obtengo los resultados
        $games = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de objetos
        
        return $games;
    }

    function valueSort($sort=null){  
        /*
            Selecciono información de TODAS las columnas de la tabla 'games', para luego dividir
            dicha información individualmente dependiendo del nombre de columna
            que se asigne mediante el parametro sort en la url de Postman.
        */ 
        $sentencia = $this->db->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = ? AND TABLE_NAME = 'games'");
        $sentencia->execute(array($sort));
        $columna = $sentencia->fetchAll(PDO::FETCH_OBJ);
        //Devuelvo todo el conjunto de información de dicha columna mediante un array.
        return count($columna);
    }

    /*
    *   Selecciona y muestra un juego por su ID de la base de datos.
    */
    public function get($id){
        $query = $this->db->prepare("SELECT * FROM games WHERE id_juego = ?");
        $query->execute([$id]);
        $game = $query->fetch(PDO::FETCH_OBJ);

        return $game;
    }

    /*
    *   Elimina un juego por su ID de la base de datos.
    */
    public function delete($id){
        $query = $this->db->prepare("DELETE FROM games WHERE id_juego = ?");
        $query->execute([$id]);
    }

    /*
    *   Inserta un juego en la base de datos.
    */
    public function insert($title, $sinopsis, $qualification, $id){
        $query = $this->db->prepare("INSERT INTO games (juego_name, sinopsis, calificacion, id_brand) VALUES (?, ?, ?, ?)");
        $query->execute(array($title, $sinopsis, $qualification, $id));

        return $this->db->lastInsertId();
    }

    /*
    *   Funcion de filtrado.
    */
    function filter($tabla = null, $name = null, $order = null) {
        if($order != null && $tabla != null){
            $query = $this->db->prepare("SELECT games.id_juego, games.juego_name, games.sinopsis, games.calificacion, games.id_brand, brands.brand_name 
        FROM games JOIN brands ON games.id_brand = brands.id_brand WHERE $tabla LIKE ? ORDER BY id_juego $order");
        }
        else if($tabla != null && $name != null){
            $query = $this->db->prepare("SELECT games.id_juego, games.juego_name, games.sinopsis, games.calificacion, games.id_brand, brands.brand_name 
            FROM games JOIN brands ON games.id_brand = brands.id_brand WHERE $tabla LIKE ?");
        }
        $query->execute(["%$name%"]);
        $object = $query->fetchAll(PDO::FETCH_OBJ);

        return $object; 
    }

    /*
    *   Funcion de paginacion.
    */
    public function getPagination($start, $products){
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $query = $this->db->prepare("SELECT id_juego, juego_name, sinopsis, calificacion, id_brand FROM games LIMIT ?, ?");
        $query->execute([$start, $products]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}