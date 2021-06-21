<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Caja {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($descripcion){
        $sql = "INSERT INTO cajas (descripcion) values ('$descripcion')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idcaja, $descripcion){
        $sql = "UPDATE cajas set descripcion = '$descripcion' where idcaja = '$idcaja'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idcaja){
        $sql = "DELETE FROM cajas where idcaja = '$idcaja'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idcaja){
        $sql = "SELECT * from cajas where idcaja = '$idcaja'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from cajas";
        return ejecutarConsulta($sql);
    }

}
?>