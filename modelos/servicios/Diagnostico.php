<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Diagnostico {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idrecepcion, $idcliente, $idpersonal, $idsucursal, $idvehiculo, $fecha_hora, $obs){

        $sql = "INSERT INTO diagnosticos (idrecepcion, idcliente, idpersonal, idsucursal ,fecha, observacion, estado) 
        VALUES ('$idrecepcion', '$idcliente', '$idpersonal', '$idsucursal', '$fecha_hora', '$obs', '1')";
        $idDiagnosticoNew = ejecutarConsulta_retornarID($sql);

        $band = true;
        $i = 0;

        while($i < count($idvehiculo)){
            $sql_detalle = "INSERT INTO diagnostico_detalle (iddiagnostico, idvehiculo) 
            VALUES ('$idDiagnosticoNew', '$idvehiculo[$i]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $i = $i + 1;
        }

        $sql2 = "UPDATE recepciones SET estado = '2' WHERE idrecepcion = '$idrecepcion'";
        ejecutarConsulta($sql2);

        return $band;
        
    }

    //implementamos un metodo para anular
    public function anular($iddiagnostico, $idrecepcion){
        $sql = "UPDATE diagnosticos SET estado='0' WHERE iddiagnostico = '$iddiagnostico'";
        $sql2 = "UPDATE recepciones SET estado = '1' WHERE idrecepcion = '$idrecepcion'";
        ejecutarConsulta($sql2);
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro
    public function mostrar($iddiagnostico){
        $sql = "SELECT d.iddiagnostico, case when cast(d.idrecepcion as char) = '0' then '-' end as idrecepcion, d.idcliente, concat(c.nombre, ' ', c.apellido) AS cliente,  
        d.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, d.idsucursal, s.descripcion AS sucursal, replace(d.fecha, ' ', 'T') AS fecha, d.observacion 
        FROM diagnosticos d JOIN clientes c ON d.idcliente = c.idcliente JOIN personales p ON d.idpersonal = p.idpersonal 
        JOIN sucursales s ON d.idsucursal = s.idsucursal WHERE d.iddiagnostico = '$iddiagnostico'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($iddiagnostico){
        $sql = "SELECT dd.iddiagnostico, dd.idvehiculo, concat(m.descripcion, ' - ', v.modelo ) AS vehiculo, v.chapa, dd.descripcion
        FROM diagnostico_detalle dd JOIN vehiculos v ON dd.idvehiculo = v.idvehiculo JOIN marcas m ON v.idmarca = m.idmarca 
        WHERE dd.iddiagnostico = '$iddiagnostico'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT d.iddiagnostico, d.idrecepcion, date(d.fecha) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(p.nombre, ' ', p.apellido) AS personal, d.estado 
        FROM diagnosticos d JOIN clientes c ON d.idcliente = c.idcliente JOIN personales p ON d.idpersonal = p.idpersonal JOIN sucursales s ON d.idsucursal = s.idsucursal 
        ORDER BY d.iddiagnostico DESC";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT d.iddiagnostico, date(d.fecha) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(p.nombre, ' ', p.apellido) AS personal, d.estado 
        FROM diagnosticos d JOIN clientes c ON d.idcliente = c.idcliente JOIN personales p ON d.idpersonal = p.idpersonal JOIN sucursales s ON d.idsucursal = s.idsucursal 
        WHERE d.estado = '1' ORDER BY d.iddiagnostico DESC";
        return ejecutarConsulta($sql);
    }
}
?>