<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class EntidadEmisora {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($descripcion, $telefono, $direccion){
        $sql = "INSERT into entidad_emisora (descripcion, telefono, direccion) values ('$descripcion', '$telefono', '$direccion')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($identidademisora, $descripcion, $telefono, $direccion){
        $sql = "UPDATE entidad_emisora set descripcion = '$descripcion', telefono = '$telefono', direccion = '$direccion'  where identidademisora = '$identidademisora'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($identidademisora){
        $sql = "DELETE from entidad_emisora where identidademisora = '$identidademisora'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($identidademisora){
        $sql = "SELECT * from entidad_emisora where identidademisora = '$identidademisora'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from entidad_emisora";
        return ejecutarConsulta($sql);
    }

}
?>