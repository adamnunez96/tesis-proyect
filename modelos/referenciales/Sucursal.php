<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Sucursal {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idciudad, $descripcion, $direccion, $telefono){
        $sql = "INSERT into sucursales (idciudad, descripcion, direccion, telefono) values ('$idciudad', '$descripcion', '$direccion', '$telefono')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idsucursal, $idciudad, $descripcion, $direccion, $telefono){
        $sql = "UPDATE sucursales set idciudad = '$idciudad', descripcion = '$descripcion', direccion = '$direccion', telefono = '$telefono' where idsucursal = '$idsucursal'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idsucursal){
        $sql = "DELETE from sucursales where idsucursal = '$idsucursal'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idsucursal){
        $sql = "SELECT * from sucursales where idsucursal = '$idsucursal'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT s.idsucursal, s.descripcion, s.idciudad, c.descripcion as ciudad, s.direccion, s.telefono from sucursales s INNER JOIN ciudades c on s.idciudad = c.idciudad";
        return ejecutarConsulta($sql);
    }

}
?>