<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Ciudad {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($descripcion){
        $sql = "INSERT into ciudades (descripcion, estado) values ('$descripcion', '1')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idciudad, $descripcion){
        $sql = "UPDATE ciudades set descripcion = '$descripcion' where idciudad = '$idciudad'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idciudad){
        $sql = "UPDATE ciudades SET estado = '0' WHERE idciudad = '$idciudad'";
        return ejecutarConsulta($sql);
    }

    public function activar($idciudad){
        $sql = "UPDATE ciudades SET estado = '1' WHERE idciudad = '$idciudad'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idciudad){
        $sql = "SELECT * from ciudades where idciudad = '$idciudad'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from ciudades";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT * from ciudades where estado = '1'";
        return ejecutarConsulta($sql);
    }

    public function validarExistencia($descripcion){
        $sql = "SELECT * from ciudades where descripcion = '$descripcion'";
        $resul = ejecutarConsulta($sql);
        return mysqli_num_rows($resul);
    }

}
?>