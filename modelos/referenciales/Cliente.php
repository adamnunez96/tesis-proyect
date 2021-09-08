<?php
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Cliente {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idciudad, $ci, $nombre, $apellido, $direccion, $telefono, $correo){
        $sql = "INSERT into clientes (idciudad, ci, nombre, apellido, direccion, telefono, correo, estado) values ('$idciudad', '$ci', '$nombre', '$apellido', '$direccion', '$telefono', '$correo', '1')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idcliente, $idciudad, $ci, $nombre, $apellido, $direccion, $telefono, $correo){
        $sql = "UPDATE clientes SET idciudad = '$idciudad', ci = '$ci', nombre = '$nombre', apellido = '$apellido', direccion = '$direccion', telefono = '$telefono', correo = '$correo' where idcliente = '$idcliente'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para desactivar registros
    public function desactivar($idcliente){
        $sql = "UPDATE clientes SET estado ='0' WHERE idcliente = '$idcliente'";
        return ejecutarConsulta($sql);
    }   

    //implementamos un metodo para activar registros
    public function activar($idcliente){
        $sql = "UPDATE clientes SET estado ='1' WHERE idcliente = '$idcliente'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idcliente){
        $sql = "SELECT * FROM clientes WHERE idcliente = '$idcliente'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT c.idcliente, c.nombre, c.apellido, c.ci, c.telefono, ciu.descripcion AS ciudad, c.direccion, c.correo, c.estado FROM clientes c JOIN ciudades ciu ON c.idciudad = ciu.idciudad";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT c.idcliente, concat(c.nombre, ' ', c.apellido) as cliente, c.ci, c.telefono, ciu.descripcion AS ciudad, c.direccion, c.correo, c.estado 
        FROM clientes c JOIN ciudades ciu ON c.idciudad = ciu.idciudad 
        WHERE c.estado = '1'";
        return ejecutarConsulta($sql);
    }

}
?>