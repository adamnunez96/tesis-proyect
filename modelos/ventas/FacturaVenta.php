<?php 
//incluimos inicialmente la conexion a la base de datos
include ("../../config/conexion.php");

class Venta {
    //implementamos nuestro constructor
    public function __construct(){

    }
    //public $idcompranew;
    //implementamos un metodo para insertar registros
    public function insertar($idservicio, $nrofactura ,$idcliente, $idpersonal, $idsucursal, $idtimbrado, $idcondicion, $idtipodoc,  
    $fecha_hora, $monto_venta, $idmercaderia, $cantidad, $precio, $total_exenta, $total_iva5, $total_iva10, $liq_iva5, $liq_iva10, $cuota){
        try {

            $idapertura = $this->obtenerAperturaCaja();

            $sql = "INSERT INTO factura_venta (nrofactura, idaperturacierre, idcliente, idpersonal, idservicio, idsucursal, idtimbrado, idtipodocumento, fecha, monto, cuotas, montoex, monto5, monto10, iva5, iva10, idcondicion, estado) 
            VALUES ('$nrofactura', '$idapertura', '$idcliente', '$idpersonal', '$idservicio', '$idsucursal', '$idtimbrado', '$idtipodoc', '$fecha_hora', '$monto_venta', '$cuota', '$total_exenta', '$total_iva5', '$total_iva10', '$liq_iva5', '$liq_iva10', '$idcondicion', '1')";
            print_r($sql);
            $idventanew = ejecutarConsulta_retornarID($sql);
            print_r($idventanew);
            $num_elementos=0;
            $sw=true;
    
            while($num_elementos < count($idmercaderia)){
    
                $sql_detalle = "INSERT INTO factura_venta_detalle (idfacturaventa, idmercaderia, cantidad, precio) 
                VALUES ('$idventanew', '$idmercaderia[$num_elementos]', '$cantidad[$num_elementos]', '$precio[$num_elementos]')";
                ejecutarConsulta($sql_detalle) or $sw = false;
    
                $num_elementos =$num_elementos + 1;
            }
    
            //aca realimazamos la insercion en la tabla libro ventas
    
            $sql2 = "INSERT INTO libro_ventas (idfacturaventa, idcliente, fecha, montoexenta, montoiva5, montoiva10, grabiva5, grabiva10, montopagado) 
            VALUES ('$idventanew', '$idcliente', '$fecha_hora', '$total_exenta', '$total_iva5', '$total_iva10', '$liq_iva5', '$liq_iva10', '$monto_venta')";
    
            ejecutarConsulta($sql2);
    
            //aca realizamos la insercion en la tablacuentas  a pagar 
    
            if($idcondicion == 1){
                $condicion = 'CONTADO';
            }else{
                $condicion = 'CREDITO';
            }
    
            $monto_cuota = $monto_venta / $cuota;
    
            $fecha = strtotime($fecha_hora);
            $fecha = date('Y-m-d H:i:s', $fecha);
            $cont = 0;
    
            for ($i=1; $i <= $cuota ; $i++) { 
    
                if($i >= 2){
                    $cont = $cont + 1;
                    $fecha = date("Y-m-d H:i:s", strtotime($fecha_hora. "+ {$cont} month"));
                }
    
                if($cuota == 1){
                    $concepto = "Cuota Nro. ". $i ."/". $cuota ." ". $condicion;
                }else{
                    $concepto = "Cuota Nro. ". $i ."/". $cuota ." ". $condicion ." ". $cuota ." MESES";
                }
    
                    $sql3 = "INSERT INTO cuentas_a_cobrar (idfacturaventa, idcliente, nrofactura, idnotacredito, idnotadebito, monto, nrocuota, cantcuotas, vencimiento, concepto, estado) 
                    VALUES ('$idventanew', '$idcliente', '$nrofactura', '0', '0', '$monto_cuota', '$i', '$cuota', '$fecha', '$concepto', '2')";
    
                ejecutarConsulta($sql3);
    
            }
            //actualizamos el valor de la factura en la tabla timbrado
            $ultimoValor = explode("-", $nrofactura);
            //print_r($ultimoValor);
            $parte3 = $ultimoValor[2];
            //print_r($parte3);
            $sql4 = "UPDATE timbrados SET nroactual = '$parte3' WHERE nrotimbrado = '$idtimbrado'";
            //print_r($sql4);
            ejecutarConsulta($sql4);

            //si corresponde a un cobro de servicio realizamos el update al estado del servicio
            if(!$idservicio == 0 || !$idservicio == "0"){
                $sql5 = "UPDATE servicios SET estado = '2' WHERE idservicio = '$idservicio'";
                ejecutarConsulta($sql5);
            }
            
            echo '
            <script>
            window.open("","_blank");
            location.href= "../../reportes/exFactura.php?id=\''.$idventanew.'\'";
            </script>';
    
            return $sw;
        } catch (Exception $e) {
            echo "error en la funcion insertar: ". $e->getMessage();
        }
       
    }

    //implementamos un metodo para anular ingresos
    public function anular($idventa){
        $sql = "UPDATE factura_venta SET estado='0' WHERE idfacturaventa = '$idventa'";

        // eliminamos la compra anulada de la tabla libro compras
        $sql2 = "DELETE FROM libro_ventas WHERE idfacturaventa = '$idventa'";

        // eliminamos la compra anulada de la tabla cuentas a pagar
        $sql3 = "DELETE FROM cuentas_a_cobrar WHERE idfacturaventa = '$idventa'";

        /*volvemos habilitar la orden de compras luego de anular la factura
        $sql4 = "UPDATE orden_compras SET estado = '1' WHERE idordencompra = '$idorden'";*/
        //ejecutarConsulta($sql2);

        ejecutarConsulta($sql2);
        ejecutarConsulta($sql3);
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idventa){
        $sql = "SELECT fv.idfacturaventa, fv.nrofactura, fv.idservicio, fv.idcliente, concat(c.nombre, ' ', c.apellido) AS cliente, fv.idpersonal, concat(per.nombre, ' ', per.apellido) AS personal, fv.idsucursal, suc.descripcion AS sucursal, fv.idcondicion, fv.idtipodocumento, td.descripcion AS tipodoc, date(fv.fecha) as fecha, fv.monto, fv.cuotas 
        FROM factura_venta fv JOIN clientes c ON fv.idcliente = c.idcliente JOIN personales per ON fv.idpersonal = per.idpersonal JOIN sucursales suc ON fv.idsucursal = suc.idsucursal JOIN tipo_documentos td ON fv.idtipodocumento = td.idtipodocumento  
        WHERE idfacturaventa = '$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idventa){
        $sql = "SELECT fvd.idfacturaventa, fvd.idmercaderia, m.descripcion, fvd.cantidad, fvd.precio, ti.tipo AS iva 
        FROM factura_venta_detalle fvd JOIN mercaderias m ON fvd.idmercaderia = m.idmercaderia JOIN tipo_impuestos ti ON m.idtipoimpuesto = ti.idtipoimpuesto
         WHERE idfacturaventa = '$idventa'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar($idsucursal){
        $sql = "SELECT fv.idfacturaventa, fv.nrofactura, date(fv.fecha) as fecha, concat(c.nombre, ' ', c.apellido) AS cliente, concat(per.nombre, ' ', per.apellido) AS personal, fv.monto, fv.estado 
        FROM factura_venta fv JOIN clientes c ON fv.idcliente = c.idcliente JOIN personales per ON fv.idpersonal = per.idpersonal JOIN formas_pago fp ON fv.idcondicion = fp.idformapago
        WHERE fv.idsucursal = '$idsucursal' ORDER BY fv.idfacturaventa DESC";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listarActivos(){
        $sql = "SELECT c.idcompra, c.nrofactura, date(c.fecha) as fecha, p.razonsocial AS proveedor, concat(per.nombre, ' ', per.apellido) AS personal, c.monto, c.estado 
        FROM compras c JOIN proveedores p ON c.idproveedor = p.idproveedor JOIN personales per ON c.idpersonal = per.idpersonal WHERE c.estado = '1'
        ORDER BY c.idcompra DESC";
        return ejecutarConsulta($sql);
    }

    public function obtenerAperturaCaja(){
        try {
            $sql = "SELECT * FROM apertura_cierre_caja WHERE fechaapertura = (SELECT fechaapertura FROM apertura_cierre_caja WHERE estado = '1' ORDER BY fechaapertura DESC) and estado = '1'";
            $rspta = ejecutarConsulta($sql);
            if($fila = $rspta->fetch_object()){
                $idapertura = $fila->idaperturacierre;
            }
            print_r($idapertura);
            return $idapertura;
        } catch (Exception $e) {
            throw "error en la funcion obtenerAperturaCaja". $e->getMessage();
        }
    }

    public function ventaCabecera($idventa){
        require_once "../../config/conexion.php";
		$sql = "SELECT DISTINCT fv.idfacturaventa, fv.idcliente, concat(c.nombre, ' ', c.apellido) AS cliente, c.ci, 
        concat(p.nombre, ' ', p.apellido) AS personal, td.descripcion as tipo_documento, fv.nrofactura, fp.descripcion as condicion, 
        fv.cuotas, date(fv.fecha) as fecha, fv.montoex, fv.monto5, fv.monto10, fv.monto as total 
        FROM factura_venta fv JOIN clientes c ON fv.idcliente = c.idcliente JOIN tipo_documentos td ON fv.idtipodocumento = td.idtipodocumento 
        JOIN personales p ON fv.idpersonal = p.idpersonal JOIN formas_pago fp ON fv.idcondicion = fp.idformapago 
        where fv.idfacturaventa = '$idventa'";
		return ejecutarConsulta($sql);
	}

	public function ventaDetalle($idventa){
		$sql = "SELECT fvd.idfacturaventa, fvd.idmercaderia, m.descripcion, fvd.cantidad, fvd.precio, ti.tipo AS iva, 
        (fvd.cantidad*precio) AS subtotal FROM factura_venta_detalle fvd JOIN mercaderias m ON fvd.idmercaderia = m.idmercaderia 
        JOIN tipo_impuestos ti ON m.idtipoimpuesto = ti.idtipoimpuesto WHERE idfacturaventa = '$idventa'";
		return ejecutarConsulta($sql);
	}

}
?>