<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Compra {
    //implementamos nuestro constructor
    public function __construct(){

    }
    public $idcompranew;
    //implementamos un metodo para insertar registros
    public function insertar($idordencompra, $nrofactura, $idproveedor, $idpersonal, $idsucursal, $idformapago, $idtipodoc, $iddeposito, 
    $fecha_hora, $obs, $monto_compra, $idmercaderia, $cantidad, $precio, $total_exenta, $total_iva5, $total_iva10, $liq_iva5, $liq_iva10, $cuota){

        $sql = "INSERT INTO compras (idordencompra, nrofactura, idproveedor, idpersonal, idsucursal, idformapago, idtipodocumento, iddeposito, fecha, obs, monto, cuotas, estado) 
        VALUES ('$idordencompra', '$nrofactura', '$idproveedor', '$idpersonal', '$idsucursal', '$idformapago', '$idtipodoc', '$iddeposito', '$fecha_hora', '$obs', '$monto_compra', '$cuota', '1')";
        
        $idcompranew = ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while($num_elementos < count($idmercaderia)){

            $sql_detalle = "INSERT INTO compras_detalle (idcompra, idmercaderia, cantidad, precio) 
            VALUES ('$idcompranew', '$idmercaderia[$num_elementos]', '$cantidad[$num_elementos]', '$precio[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos =$num_elementos + 1;
        }

        //aca realimazamos la insercion en la tabla libro compras

        $sql2 = "INSERT INTO libro_compras (idcompra, idproveedor, fecha, montoexenta, montoiva5, montoiva10, grabiva5, grabiva10, montopagado) 
        VALUES ('$idcompranew', '$idproveedor', '$fecha_hora', '$total_exenta', '$total_iva5', '$total_iva10', '$liq_iva5', '$liq_iva10', '$monto_compra')";

        ejecutarConsulta($sql2);

        //aca realizamos la insercion en la tabla cuentas a pagar 

        if($idformapago == 1){
            $formaPago = 'CONTADO';
        }else{
            $formaPago = 'CREDITO';
        }

        $monto_cuota = $monto_compra / $cuota;

        $fecha = strtotime($fecha_hora);
        $fecha = date('Y-m-d H:i:s', $fecha);
        $cont = 0;

        for ($i=1; $i <= $cuota ; $i++) { 

            if($i >= 2){
                $cont = $cont + 1;
                $fecha = date("Y-m-d H:i:s", strtotime($fecha_hora. "+ {$cont} month"));
            }

            if($cuota == 1){
                $concepto = "Cuota Nro. ". $i ."/". $cuota ." ". $formaPago;
            }else{
                $concepto = "Cuota Nro. ". $i ."/". $cuota ." ". $formaPago ." ". $cuota ." MESES";
            }

                $sql3 = "INSERT INTO cuentas_a_pagar (idcompra, idproveedor, nrofactura, idnotacredidebi, totalcuota, nrocuota, montocuota, fechavto, obs, estado) 
                VALUES ('$idcompranew', '$idproveedor', '$nrofactura', '0', '$cuota', '$i', '$monto_cuota', '$fecha','$concepto', '1')";

            ejecutarConsulta($sql3);

        }

        return $sw;

    }

    //implementamos un metodo para anular ingresos
    public function anular($idcompra){
        $sql = "UPDATE compras SET estado='0' WHERE idcompra = '$idcompra'";

        // eliminamos la compra anulada de la tabla libro compras
        $sql2 = "DELETE FROM libro_compras WHERE idcompra = '$idcompra'";

        // eliminamos la compra anulada de la tabla cuentas a pagar
        $sql3 = "DELETE FROM cuentas_a_pagar WHERE idcompra = '$idcompra'";
        ejecutarConsulta($sql2);
        ejecutarConsulta($sql3);
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idcompra){
        $sql = "SELECT c.idcompra, c.idordencompra, c.nrofactura, c.idproveedor, p.razonsocial AS proveedor, c.idpersonal, concat(per.nombre, ' ', per.apellido) AS personal, c.idsucursal, suc.descripcion AS sucursal, c.idformapago, fp.descripcion AS formapago, c.idtipodocumento, td.descripcion AS tipodoc, c.iddeposito, d.descripcion AS deposito, date(c.fecha) as fecha, c.obs, c.monto 
        FROM compras c JOIN proveedores p ON c.idproveedor = p.idproveedor JOIN personales per ON c.idpersonal = per.idpersonal JOIN sucursales suc ON c.idsucursal = suc.idsucursal JOIN formas_pago fp ON c.idformapago = fp.idformapago JOIN tipo_documentos td ON c.idtipodocumento = td.idtipodocumento JOIN depositos d ON c.iddeposito = d.iddeposito 
        WHERE idcompra = '$idcompra'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idcompra){
        $sql = "SELECT cd.idcompra, cd.idmercaderia, m.descripcion, cd.cantidad, cd.precio, ti.tipo AS iva
        FROM compras_detalle cd JOIN mercaderias m ON cd.idmercaderia = m.idmercaderia JOIN tipo_impuestos ti 
        ON m.idtipoimpuesto = ti.idtipoimpuesto WHERE idcompra = '$idcompra'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT c.idcompra, c.nrofactura, date(c.fecha) as fecha, p.razonsocial AS proveedor, concat(per.nombre, ' ', per.apellido) AS personal, c.monto, c.estado 
        FROM compras c JOIN proveedores p ON c.idproveedor = p.idproveedor JOIN personales per ON c.idpersonal = per.idpersonal 
        ORDER BY c.idcompra DESC";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para insertar la compra en la tabla libro de compras
    public function insertarLibroCompras($idproveedor, $fecha, $total_exenta, $total_iva5, $total_iva10, $liq_iva5, $liq_iva10, $total_compra){
 
        $sql = "INSERT INTO libro_compras (idcompra, idproveedor, fecha, montoexenta, montoiva5, montoiva10, grabiva5, grabiva10, montopagado) 
        VALUES ('$this->idcompranew', '$idproveedor', '$fecha', '$total_exenta', '$total_iva5', '$total_iva10', '$liq_iva5', '$liq_iva10', '$total_compra')";

        ejecutarConsulta($sql);
    }

}
?>