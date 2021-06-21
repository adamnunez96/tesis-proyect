<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class FormaPago {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($descripcion, $cuota){
        $sql = "INSERT into formas_pago (descripcion, cuota) values ('$descripcion', '$cuota')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idformapago, $descripcion, $cuota){
        $sql = "UPDATE formas_pago set descripcion = '$descripcion', cuota = '$cuota' where idformapago = '$idformapago'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idformapago){
        $sql = "DELETE from formas_pago where idformapago = '$idformapago'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idformapago){
        $sql = "SELECT * from formas_pago where idformapago = '$idformapago'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from formas_pago";
        return ejecutarConsulta($sql);
    }

}
?>