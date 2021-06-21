<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Tarjeta {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idMarcaTarjeta, $idEntidadEmisora, $descripcion){
        $sql = "INSERT INTO tarjetas (idmarcatarjeta, identidademisora, descripcion) values ('$idMarcaTarjeta', '$idEntidadEmisora','$descripcion')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idtarjeta, $idMarcaTarjeta, $idEntidadEmisora, $descripcion){
        $sql = "UPDATE tarjetas SET idmarcatarjeta = '$idMarcaTarjeta', identidademisora = '$idEntidadEmisora', descripcion = '$descripcion' where idtarjeta = '$idtarjeta'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idtarjeta){
        $sql = "DELETE FROM tarjetas WHERE idtarjeta = '$idtarjeta'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idtarjeta){
        $sql = "SELECT * FROM tarjetas where idtarjeta = '$idtarjeta'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT t.idtarjeta, t.descripcion, mt.descripcion AS marca, e.descripcion AS entidad FROM tarjetas t JOIN marcas_tarjetas mt ON t.idmarcatarjeta = mt.idmarcatarjeta JOIN entidad_emisora e ON t.identidademisora = e.identidademisora";
        return ejecutarConsulta($sql);
    }

}
?>