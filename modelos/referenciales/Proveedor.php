<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Proveedor {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idciudad, $razonSocial, $ruc, $direccion, $telefono, $correo){
        $sql = "INSERT INTO proveedores (idciudad, razonsocial, ruc, direccion, telefono, correo, estado) values ('$idciudad', '$razonSocial', '$ruc', '$direccion', '$telefono', '$correo', '1')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idproveedor, $idciudad, $razonsocial, $ruc, $direccion, $telefono, $correo){
        $sql = "UPDATE proveedores set idciudad = '$idciudad', razonsocial = '$razonsocial', ruc = '$ruc', direccion = '$direccion', telefono = '$telefono', correo ='$correo' WHERE idproveedor = '$idproveedor'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para desactivar registros
    public function desactivar($idproveedor){
        $sql = "UPDATE proveedores SET estado ='0' WHERE idproveedor = '$idproveedor'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para activar registros
    public function activar($idproveedor){
        $sql = "UPDATE proveedores SET estado ='1' WHERE idproveedor = '$idproveedor'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idproveedor){
        $sql = "SELECT * FROM proveedores where idproveedor = '$idproveedor'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT p.idproveedor, p.razonsocial, p.ruc, p.idciudad, c.descripcion AS ciudad, p.direccion, p.telefono, p.correo, p.estado 
        FROM proveedores p join ciudades c ON p.idciudad = c.idciudad";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros activos a utilizar en los movimientos
    public function listarActivos(){
        $sql = "SELECT p.idproveedor, p.razonsocial, p.ruc, p.idciudad, c.descripcion AS ciudad, p.direccion, p.telefono, p.correo, p.estado 
        FROM proveedores p join ciudades c ON p.idciudad = c.idciudad WHERE p.estado = '1'";
        return ejecutarConsulta($sql);
    }

}
?>