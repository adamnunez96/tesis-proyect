<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class OrdenCompra {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idpedido, $idsucursal, $idpersonal, $idproveedor, $fecha_hora, $observacion, $monto_compra, $idmercaderia, $cantidad, $precio){

        $sql = "INSERT INTO orden_compras (idpedido, idsucursal, idpersonal, idproveedor ,fecha, obs, monto, estado) 
        VALUES ('$idpedido', '$idsucursal', '$idpersonal', '$idproveedor', '$fecha_hora', '$observacion', '$monto_compra', '1')";
        //return ejecutarConsulta($sql);
        $idordennew = ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while($num_elementos < count($idmercaderia)){

            $sql_detalle = "INSERT INTO orden_compras_detalle (idordencompra, idmercaderia, cantidad, precio) 
            VALUES ('$idordennew', '$idmercaderia[$num_elementos]', '$cantidad[$num_elementos]', '$precio[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos =$num_elementos + 1;
        }

        $sql2 = "UPDATE pedido_compra SET estado = '2' WHERE idpedido = '$idpedido'";
        ejecutarConsulta($sql2);
        return $sw;
    }

    //implementamos un metodo para anular ingresos
    public function anular($idorden, $idpedido){
        $sql = "UPDATE orden_compras SET estado='0' WHERE idordencompra = '$idorden'";
        $sql2 = "UPDATE pedido_compra SET estado = '1' WHERE idpedido = '$idpedido'";
        ejecutarConsulta($sql2);
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idorden){
        $sql = "SELECT oc.idordencompra, oc.idpedido, oc.idsucursal, s.descripcion AS sucursal, oc.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, oc.idproveedor, pro.razonsocial AS proveedor, replace(oc.fecha, ' ', 'T') AS fecha, oc.obs, oc.monto 
        FROM orden_compras oc JOIN sucursales s ON oc.idsucursal = s.idsucursal JOIN personales p ON oc.idpersonal = p.idpersonal JOIN proveedores pro ON oc.idproveedor = pro.idproveedor 
        WHERE idordencompra = '$idorden'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idorden){
        $sql = "SELECT ocd.idordencompra, ocd.idmercaderia, m.descripcion, ocd.cantidad, ocd.precio, ti.tipo AS iva
        FROM orden_compras_detalle ocd JOIN mercaderias m ON ocd.idmercaderia = m.idmercaderia JOIN tipo_impuestos ti ON m.idtipoimpuesto = ti.idtipoimpuesto 
        WHERE idordencompra = '$idorden'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar($idsucursal){
        $sql = "SELECT oc.idordencompra, oc.idpedido, date(oc.fecha) AS fecha, pro.razonsocial AS proveedor, concat(p.nombre, ' ', p.apellido) AS personal, oc.monto, oc.estado 
        FROM orden_compras oc JOIN personales p ON oc.idpersonal = p.idpersonal JOIN proveedores pro ON oc.idproveedor = pro.idproveedor 
        WHERE oc.idsucursal = '$idsucursal' ORDER BY oc.idordencompra DESC";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT oc.idordencompra, date(oc.fecha) AS fecha, pro.razonsocial AS proveedor, concat(p.nombre, ' ', p.apellido) AS personal, oc.monto, oc.estado 
        FROM orden_compras oc JOIN personales p ON oc.idpersonal = p.idpersonal JOIN proveedores pro ON oc.idproveedor = pro.idproveedor 
        WHERE oc.estado = '1' ORDER BY oc.idordencompra DESC";
        return ejecutarConsulta($sql);
    }
}
?>