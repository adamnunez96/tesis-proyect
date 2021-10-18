<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class OrdenTrabajo {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idpresupuesto, $idcliente, $idpersonal, $idsucursal, $fecha_hora, $obs, $idvehiculo, $idmercaderia, $cantidad, $precio){
        $sql = "INSERT INTO orden_trabajos (idpresupuestoservicio, idcliente, idpersonal, idsucursal ,fechainicio, obs, estado) 
        VALUES ('$idpresupuesto', '$idcliente', '$idpersonal', '$idsucursal', '$fecha_hora', '$obs', '1')";
        $idOrdenoNew = ejecutarConsulta_retornarID($sql);

        $band = true;
        $i = 0;

        while($i < count($idvehiculo)){
            $sql_vehiculo = "INSERT INTO oden_trabajo_detalle (idordentrabajo, idvehiculo) 
            VALUES ('$idOrdenoNew', '$idvehiculo[$i]')";
            ejecutarConsulta($sql_vehiculo);

            $i++;
        }

        $j = 0;
        while($j < count($idmercaderia)){
            $sql_mercaderia = "INSERT INTO oden_trabajo_detalle (idordentrabajo, idvehiculo, idmercaderia, cantidad, precio) 
            VALUES ('$idOrdenoNew', '$idvehiculo[$j]', '$idmercaderia[$j]', '$cantidad[$j]', '$precio[$j]')";
            ejecutarConsulta($sql_mercaderia) or $sw = false;

            $j++;
        }

        $sql2 = "UPDATE presupuestos_servicios SET estado = '2' WHERE idpresupuestoservicio = '$idpresupuesto'";
        ejecutarConsulta($sql2);

        return $band;
        
    }

    //implementamos un metodo para anular
    public function anular($idorden, $idpresupuestoservicio){
        $sql = "UPDATE orden_trabajos SET estado='0' WHERE idordentrabajo = '$idorden'";
        $sql2 = "UPDATE presupuestos_servicios SET estado = '1' WHERE idpresupuestoservicio = '$idpresupuestoservicio'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro
    public function mostrar($idorden){
        $sql = "SELECT o.idordentrabajo, case when cast(o.idpresupuestoservicio as char) = '0' then '-' else cast(o.idpresupuestoservicio as char) end as idpresupuesto, o.idcliente, concat(c.nombre, ' ', c.apellido) AS cliente,  
        o.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, o.idsucursal, s.descripcion AS sucursal, replace(o.fechainicio, ' ', 'T') AS fecha, o.obs 
        FROM orden_trabajos o JOIN clientes c ON o.idcliente = c.idcliente JOIN personales p ON o.idpersonal = p.idpersonal 
        JOIN sucursales s ON o.idsucursal = s.idsucursal WHERE o.idordentrabajo = '$idorden'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idorden){
        $sql = "SELECT od.idordentrabajo, od.idvehiculo, concat(m.descripcion, ' - ', v.modelo) AS vehiculo, v.chapa, od.idmercaderia, concat(mer.descripcion, ' ',ma.descripcion) as mercaderia, od.cantidad, od.precio
        FROM oden_trabajo_detalle od JOIN vehiculos v ON od.idvehiculo = v.idvehiculo JOIN marcas m ON v.idmarca = m.idmarca JOIN mercaderias mer ON od.idmercaderia = mer.idmercaderia JOIN marcas ma ON mer.idmarca = ma.idmarca 
        WHERE od.idordentrabajo = '$idorden'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT od.idordentrabajo, od.idpresupuestoservicio, date(od.fechainicio) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(p.nombre, ' ', p.apellido) AS personal, od.estado 
        FROM orden_trabajos od JOIN clientes c ON od.idcliente = c.idcliente JOIN personales p ON od.idpersonal = p.idpersonal JOIN sucursales s ON od.idsucursal = s.idsucursal 
        ORDER BY od.idordentrabajo DESC";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT od.idordentrabajo, date(od.fechainicio) AS fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(p.nombre, ' ', p.apellido) AS personal, od.estado 
        FROM orden_trabajos od JOIN clientes c ON od.idcliente = c.idcliente JOIN personales p ON od.idpersonal = p.idpersonal JOIN sucursales s ON od.idsucursal = s.idsucursal  
        WHERE od.estado = '1' ORDER BY od.idordentrabajo DESC";
        return ejecutarConsulta($sql);
    }
}
?>