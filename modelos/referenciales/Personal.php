<?php
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Personal {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idusuario, $idciudad, $idsucursal, $nombre, $apellido, $documento, $direccion, $telefono, $cargo, $correo, $imagen){
        $sql = "INSERT into personales (idusuario, idciudad, idsucursal, nombre, apellido, documento, direccion, telefono, cargo, correo, imagen, estado) values 
        ('$idusuario', '$idciudad', '$idsucursal', '$nombre', '$apellido', '$documento','$direccion', '$telefono', '$cargo', '$correo', '$imagen', '1')";

        print_r("INSERT into personales (idusuario, idciudad, idsucursal, nombre, apellido, documento, direccion, telefono, cargo, correo, imagen, estado) values 
        ('$idusuario', '$idciudad', '$idsucursal', '$nombre', '$apellido', '$documento', '$direccion', '$telefono', '$cargo', '$correo', '$imagen', '1')");
        
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idpersonal, $idusuario, $idciudad, $idsucursal, $nombre, $apellido, $documento, $direccion, $telefono, $cargo, $correo, $imagen){
        $sql = "UPDATE personales SET idusuario = '$idusuario', idciudad = '$idciudad', idsucursal = '$idsucursal', nombre = '$nombre', apellido = '$apellido', 
        documento = '$documento',direccion = '$direccion', telefono = '$telefono', cargo = '$cargo', correo = '$correo', imagen = '$imagen' 
        where idpersonal = '$idpersonal'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para desactivar registros
    public function desactivar($idpersonal){
        $sql = "UPDATE personales SET estado ='0' WHERE idpersonal = '$idpersonal'";
        return ejecutarConsulta($sql);
    }   

    //implementamos un metodo para activar registros
    public function activar($idpersonal){
        $sql = "UPDATE personales SET estado ='1' WHERE idpersonal = '$idpersonal'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idpersonal){
        $sql = "SELECT * FROM personales WHERE idpersonal = '$idpersonal'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT p.idpersonal, p.nombre, p.apellido, u.usuario, p.cargo, p.telefono, p.imagen, p.estado
        FROM personales p JOIN usuarios u ON p.idusuario = u.idusuario JOIN ciudades c ON p.idciudad = c.idciudad";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT p.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, u.usuario, p.cargo, p.telefono, p.imagen, p.estado
        FROM personales p JOIN usuarios u ON p.idusuario = u.idusuario JOIN ciudades c ON p.idciudad = c.idciudad 
        WHERE p.estado = '1'";
        return ejecutarConsulta($sql);
    }

}
?>