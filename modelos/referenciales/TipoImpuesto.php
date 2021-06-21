<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class TipoImpuesto {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($descripcion, $tipo){
        $sql = "INSERT INTO tipo_impuestos (descripcion, tipo) values ('$descripcion', '$tipo')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idtipoimpuesto, $descripcion, $tipo){
        $sql = "UPDATE tipo_impuestos set descripcion = '$descripcion', tipo = '$tipo' where idtipoimpuesto = '$idtipoimpuesto'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idtipoimpuesto){
        $sql = "DELETE FROM tipo_impuestos WHERE idtipoimpuesto = '$idtipoimpuesto'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idtipoimpuesto){
        $sql = "SELECT * from tipo_impuestos WHERE idtipoimpuesto = '$idtipoimpuesto'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from tipo_impuestos";
        return ejecutarConsulta($sql);
    }

}
?>