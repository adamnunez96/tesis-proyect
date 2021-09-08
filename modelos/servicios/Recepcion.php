<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Recepcion {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idcliente, $idvehiculo, $idpersonal, $idsucursal, $fecha_hora, $motivo, $obs){

        $sql = "INSERT INTO recepciones (idcliente, idvehiculo, idpersonal, idsucursal ,fecha, motivo, descripcion, estado) 
        VALUES ('$idcliente', '$idvehiculo', '$idpersonal', '$idsucursal', '$fecha_hora', '$motivo', '$obs', '1')";
        //return ejecutarConsulta($sql);
         
        return ejecutarConsulta($sql);
    }

    public function modificar($idrecepcion, $idcliente, $idvehiculo, $idpersonal, $idsucursal, $motivo, $obs){

        $sql = "UPDATE recepciones SET idcliente = '$idcliente', idvehiculo = '$idvehiculo', idpersonal = '$idpersonal', idsucursal = '$idsucursal',  
        motivo = '$motivo', descripcion = '$obs' WHERE idrecepcion = '$idrecepcion'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para anular ingresos
    public function anular($idrecepcion){
        $sql = "UPDATE recepciones SET estado='0' WHERE idrecepcion = '$idrecepcion'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idrecepcion){
        $sql = "SELECT r.idrecepcion, r.idcliente, concat(c.nombre, ' ', c.apellido) AS cliente, r.idvehiculo, concat(m.descripcion, ' ', v.modelo ) AS vehiculo, v.chapa, 
        r.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, r.idsucursal, s.descripcion AS sucursal, replace(r.fecha, ' ', 'T') AS fecha, r.motivo, r.descripcion 
        FROM recepciones r JOIN clientes c ON r.idcliente = c.idcliente JOIN vehiculos v ON r.idvehiculo = v.idvehiculo JOIN personales p ON r.idpersonal = p.idpersonal 
        JOIN sucursales s ON r.idsucursal = s.idsucursal JOIN marcas m ON v.idmarca = m.idmarca WHERE r.idrecepcion = '$idrecepcion'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT r.idrecepcion, date(r.fecha) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(m.descripcion, ' ', v.modelo, ' ',v.chapa ) AS vehiculo, r.estado 
        FROM recepciones r JOIN clientes c ON r.idcliente = c.idcliente JOIN vehiculos v ON r.idvehiculo = v.idvehiculo JOIN personales p ON r.idpersonal = p.idpersonal JOIN sucursales s ON r.idsucursal = s.idsucursal JOIN marcas m ON v.idmarca = m.idmarca 
        ORDER BY r.idrecepcion DESC";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT r.idrecepcion, date(r.fecha) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(m.descripcion, ' ', v.modelo, ' ',v.chapa) AS vehiculo, r.estado 
        FROM recepciones r JOIN clientes c ON r.idcliente = c.idcliente JOIN vehiculos v ON r.idvehiculo = v.idvehiculo JOIN personales p ON r.idpersonal = p.idpersonal JOIN sucursales s ON r.idsucursal = s.idsucursal JOIN marcas m ON v.idmarca = m.idmarca 
        WHERE r.estado = '1' ORDER BY r.idrecepcion DESC";
        return ejecutarConsulta($sql);
    }

    public function listarDetalle($idrecepcion){
        $sql = "SELECT r.idrecepcion, r.idvehiculo, concat(m.descripcion, ' ', v.modelo ) AS vehiculo, v.chapa
        FROM recepciones r JOIN vehiculos v ON r.idvehiculo = v.idvehiculo JOIN marcas m ON v.idmarca = m.idmarca
        WHERE r.idrecepcion = '$idrecepcion'";
        return ejecutarConsulta($sql);
    }
}
?>