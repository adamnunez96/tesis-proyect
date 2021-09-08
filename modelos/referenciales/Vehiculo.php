<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Vehiculo {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idmarca, $modelo, $chapa, $observacion, $anho){
        $sql = "INSERT into vehiculos (idmarca, modelo, chapa, observacion, anho, estado) values ('$idmarca', '$modelo', '$chapa', '$observacion', '$anho', '1')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idvehiculo, $idmarca, $modelo, $chapa, $observacion, $anho){
        $sql = "UPDATE vehiculos SET idmarca = '$idmarca', modelo = '$modelo', chapa = '$chapa', observacion = 
        '$observacion', anho = '$anho' where idvehiculo = '$idvehiculo'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para desactivar registros
    public function desactivar($idvehiculo){
        $sql = "UPDATE vehiculos SET estado ='0' WHERE idvehiculo = '$idvehiculo'";
        return ejecutarConsulta($sql);
    }   

    //implementamos un metodo para activar registros
    public function activar($idvehiculo){
        $sql = "UPDATE vehiculos SET estado ='1' WHERE idvehiculo = '$idvehiculo'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idvehiculo){
        $sql = "SELECT * FROM vehiculos where idvehiculo = '$idvehiculo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT v.idvehiculo, v.modelo, m.descripcion AS marca, v.chapa, v.observacion, v.anho, v.estado FROM vehiculos v JOIN marcas m ON v.idmarca = m.idmarca";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listarActivos(){
        $sql = "SELECT v.idvehiculo, concat(m.descripcion, ' - ' ,v.modelo) AS vehiculo, v.chapa, v.observacion, v.anho, v.estado FROM vehiculos v JOIN marcas m ON v.idmarca = m.idmarca 
        WHERE v.estado = '1'";
        return ejecutarConsulta($sql);
    }

}
?>