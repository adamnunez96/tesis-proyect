<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Deposito {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idsucursal, $descripcion){
        $sql = "INSERT into depositos (idsucursal, descripcion) values ('$idsucursal', '$descripcion')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($iddeposito, $idsucursal, $descripcion){
        $sql = "UPDATE depositos set idsucursal = '$idsucursal', descripcion = '$descripcion' where iddeposito = '$iddeposito'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($iddeposito){
        $sql = "UPDATE depositos SET estado = '0' WHERE iddeposito = '$iddeposito'";
        return ejecutarConsulta($sql);
    }

    public function activar($iddeposito){
        $sql = "UPDATE depositos SET estado = '1' WHERE iddeposito = '$iddeposito'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($iddeposito){
        $sql = "SELECT * FROM depositos where iddeposito = '$iddeposito'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT d.iddeposito, d.descripcion, d.idsucursal, s.descripcion AS sucursal, d.estado FROM depositos d JOIN sucursales s ON d.idsucursal = s.idsucursal";
        return ejecutarConsulta($sql);
    }

    public function listarDeposito($idsucursal){
        $sql = "SELECT d.iddeposito, d.descripcion, d.idsucursal, s.descripcion AS sucursal 
        FROM depositos d JOIN sucursales s ON d.idsucursal = s.idsucursal 
        WHERE d.idsucursal = '$idsucursal'";
        return ejecutarConsulta($sql);
    }

    public function validarExistencia($descripcion){
        $sql = "SELECT * from depositos where descripcion = '$descripcion'";
        $resul = ejecutarConsulta($sql);
        return mysqli_num_rows($resul);
    }

}
?>