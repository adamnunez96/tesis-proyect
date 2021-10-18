<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Marca {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($descripcion){
        $sql = "INSERT into marcas (descripcion) values ('$descripcion')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idmarca, $descripcion){
        $sql = "UPDATE marcas set descripcion = '$descripcion' where idmarca = '$idmarca'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idmarca){
        $sql = "DELETE from marcas where idmarca = '$idmarca'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idmarca){
        $sql = "SELECT * from marcas where idmarca = '$idmarca'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from marcas";
        return ejecutarConsulta($sql);
    }

    public function validarExistencia($descripcion){
        $sql = "SELECT * from marcas where descripcion = '$descripcion'";
        $resul = ejecutarConsulta($sql);
        return mysqli_num_rows($resul);
    }

}
?>