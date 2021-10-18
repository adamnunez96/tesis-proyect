<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Presupuesto {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($iddiagnostico, $idcliente, $idpersonal, $idsucursal, $fecha_hora, $obs, $idvehiculo, $idmercaderia, $cantidad, $precio){
        $sql = "INSERT INTO presupuestos_servicios (iddiagnostico, idcliente, idpersonal, idsucursal ,fecha, obs, estado) 
        VALUES ('$iddiagnostico', '$idcliente', '$idpersonal', '$idsucursal', '$fecha_hora', '$obs', '1')";
        $idPresupuestoNew = ejecutarConsulta_retornarID($sql);

        $band = true;
        $i = 0;

        while($i < count($idvehiculo)){
            $sql_vehiculo = "INSERT INTO presupuesto_servicio_detalle (idpresupuestoservicio, idvehiculo) 
            VALUES ('$idPresupuestoNew', '$idvehiculo[$i]')";
            ejecutarConsulta($sql_vehiculo);

            $i++;
        }

        $j = 0;
        while($j < count($idmercaderia)){
            $sql_mercaderia = "INSERT INTO presupuesto_servicio_detalle (idpresupuestoservicio, idvehiculo, idmercaderia, cantidad, precio) 
            VALUES ('$idPresupuestoNew', '$idvehiculo[$j]', '$idmercaderia[$j]', '$cantidad[$j]', '$precio[$j]')";
            ejecutarConsulta($sql_mercaderia) or $sw = false;

            $j++;
        }

        $sql2 = "UPDATE diagnosticos SET estado = '2' WHERE iddiagnostico = '$iddiagnostico'";
        ejecutarConsulta($sql2);

        return $band;
        
    }

    //implementamos un metodo para anular
    public function anular($idpresupuesto, $iddiagnostico){
        $sql = "UPDATE presupuestos_servicios SET estado='0' WHERE idpresupuestoservicio = '$idpresupuesto'";
        $sql2 = "UPDATE diagnosticos SET estado = '1' WHERE iddiagnostico = '$iddiagnostico'";
        ejecutarConsulta($sql2);
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro
    public function mostrar($idpresupuesto){
        $sql = "SELECT ps.idpresupuestoservicio, case when cast(ps.iddiagnostico as char) = '0' then '-' else cast(ps.iddiagnostico as char) end as iddiagnostico, ps.idcliente, concat(c.nombre, ' ', c.apellido) AS cliente,  
        ps.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, ps.idsucursal, s.descripcion AS sucursal, replace(ps.fecha, ' ', 'T') AS fecha, ps.obs 
        FROM presupuestos_servicios ps JOIN clientes c ON ps.idcliente = c.idcliente JOIN personales p ON ps.idpersonal = p.idpersonal 
        JOIN sucursales s ON ps.idsucursal = s.idsucursal WHERE ps.idpresupuestoservicio = '$idpresupuesto'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idpresupuesto){
        $sql = "SELECT psd.idpresupuestoservicio, psd.idvehiculo, concat(m.descripcion, ' - ', v.modelo) AS vehiculo, v.chapa, psd.idmercaderia, concat(mer.descripcion, ' ',ma.descripcion) as mercaderia, psd.cantidad, psd.precio
        FROM presupuesto_servicio_detalle psd JOIN vehiculos v ON psd.idvehiculo = v.idvehiculo JOIN marcas m ON v.idmarca = m.idmarca JOIN mercaderias mer ON psd.idmercaderia = mer.idmercaderia JOIN marcas ma ON mer.idmarca = ma.idmarca 
        WHERE psd.idpresupuestoservicio = '$idpresupuesto'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT ps.idpresupuestoservicio, ps.iddiagnostico, date(ps.fecha) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(p.nombre, ' ', p.apellido) AS personal, ps.estado 
        FROM presupuestos_servicios ps JOIN clientes c ON ps.idcliente = c.idcliente JOIN personales p ON ps.idpersonal = p.idpersonal JOIN sucursales s ON ps.idsucursal = s.idsucursal 
        ORDER BY ps.idpresupuestoservicio DESC";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT ps.idpresupuestoservicio, date(ps.fecha) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(p.nombre, ' ', p.apellido) AS personal, ps.estado 
        FROM presupuestos_servicios ps JOIN clientes c ON ps.idcliente = c.idcliente JOIN personales p ON ps.idpersonal = p.idpersonal JOIN sucursales s ON ps.idsucursal = s.idsucursal  
        WHERE ps.estado = '1' ORDER BY ps.idpresupuestoservicio DESC";
        return ejecutarConsulta($sql);
    }
}
?>