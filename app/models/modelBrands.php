<?php

class brandsModel{
    
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=listajuegos;charset=utf8', 'root', '');
    }

    function getBrandById($id){
        $query = $this->db->prepare("SELECT * FROM brands WHERE brand_name = ?");
        $query->execute([$id]);

        $brand = $query->fetch(PDO::FETCH_OBJ);

        return $brand;
    }
    
    function getAllBrands(){
        //Conexion a db ya hecha por el constructor de la clase
        
        //Ejecuto la sentencia (2 subpasos)
        $query = $this->db->prepare( "SELECT * FROM  brands");
        $query->execute();
        //Obtengo los resultados
        $brands = $query->fetchAll(PDO::FETCH_OBJ);
        return $brands;
    }
}
