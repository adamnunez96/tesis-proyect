<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class MarcaTarjeta {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($descripcion){
        $sql = "INSERT into marcas_tarjetas (descripcion) values ('$descripcion')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idmarcatarjeta, $descripcion){
        $sql = "UPDATE marcas_tarjetas set descripcion = '$descripcion' where idmarcatarjeta = '$idmarcatarjeta'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idmarcatarjeta){
        $sql = "DELETE from marcas_tarjetas where idmarcatarjeta = '$idmarcatarjeta'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idmarcatarjeta){
        $sql = "SELECT * from marcas_tarjetas where idmarcatarjeta = '$idmarcatarjeta'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from marcas_tarjetas";
        return ejecutarConsulta($sql);
    }

    public function validarExistencia($descripcion){
        $sql = "SELECT * from marcas_tarjetas where descripcion = '$descripcion'";
        $resul = ejecutarConsulta($sql);
        return mysqli_num_rows($resul);
    }

}
?>