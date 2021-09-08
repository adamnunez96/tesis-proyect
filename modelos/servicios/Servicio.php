<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Servicio {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idordencompa, $idcliente, $idpersonal, $idsucursal, $fecha_hora, $obs, $idvehiculo, $idmercaderia, $cantidad, $precio){
            $sql = "INSERT INTO servicios (idordentrabajo, idcliente, idpersonal, idsucursal ,fecha, observacion, estado) 
            VALUES ('$idordencompa', '$idcliente', '$idpersonal', '$idsucursal', '$fecha_hora', '$obs', '1')";
            $idServicioNew = ejecutarConsulta_retornarID($sql);

            $band = true;
            $i = 0;

            while($i < count($idvehiculo)){
                $sql_vehiculo = "INSERT INTO servicios_detalle (idservicio, idvehiculo) 
                VALUES ('$idServicioNew', '$idvehiculo[$i]')";
                ejecutarConsulta($sql_vehiculo);

                $i++;
            }

            $j = 0;
            while($j < count($idmercaderia)){
                $sql_mercaderia = "INSERT INTO servicios_detalle (idservicio, idvehiculo, idmercaderia, cantidad, precio) 
                VALUES ('$idServicioNew', '$idvehiculo[$j]', '$idmercaderia[$j]', '$cantidad[$j]', '$precio[$j]')";
                ejecutarConsulta($sql_mercaderia) or $sw = false;

                $j++;
            }

            return $band;
        
    }

    //implementamos un metodo para anular
    public function anular($idservicio){
        $sql = "UPDATE servicios SET estado='0' WHERE idservicio = '$idservicio'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro
    public function mostrar($idservicio){
        $sql = "SELECT s.idservicio, case when cast(s.idordentrabajo as char) = '0' then '-' else cast(s.idordentrabajo as char) end as idordentrabajo, 
        s.idcliente, concat(c.nombre, ' ', c.apellido) AS cliente, s.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, suc.idsucursal, 
        suc.descripcion AS sucursal, replace(s.fecha, ' ', 'T') AS fecha, s.observacion 
        FROM servicios s JOIN clientes c ON s.idcliente = c.idcliente JOIN personales p ON s.idpersonal = p.idpersonal 
        JOIN sucursales suc ON s.idsucursal = suc.idsucursal WHERE s.idservicio = '$idservicio'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idservicio){
        $sql = "SELECT sd.idservicio, sd.idvehiculo, concat(m.descripcion, ' - ', v.modelo) AS vehiculo, v.chapa, sd.idmercaderia, concat(mer.descripcion, ' ',ma.descripcion) as mercaderia, sd.cantidad, sd.precio
        FROM servicios_detalle sd JOIN vehiculos v ON sd.idvehiculo = v.idvehiculo JOIN marcas m ON v.idmarca = m.idmarca JOIN mercaderias mer ON sd.idmercaderia = mer.idmercaderia JOIN marcas ma ON mer.idmarca = ma.idmarca 
        WHERE sd.idservicio = '$idservicio'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT s.idservicio, date(s.fecha) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(p.nombre, ' ', p.apellido) AS personal, s.estado 
        FROM servicios s JOIN clientes c ON s.idcliente = c.idcliente JOIN personales p ON s.idpersonal = p.idpersonal JOIN sucursales suc ON s.idsucursal = suc.idsucursal 
        ORDER BY s.idservicio DESC";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT s.idservicio, date(s.fecha) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(p.nombre, ' ', p.apellido) AS personal, s.estado 
        FROM servicios s JOIN clientes c ON s.idcliente = c.idcliente JOIN personales p ON s.idpersonal = p.idpersonal JOIN sucursales suc ON s.idsucursal = suc.idsucursal 
        WHERE s.estado = '1' ORDER BY s.idservicio DESC";
        return ejecutarConsulta($sql);
    }
}
?>