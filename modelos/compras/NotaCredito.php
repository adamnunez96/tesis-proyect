<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class NotaCredito {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idcompra, $nronotacredito, $timbrado, $idproveedor, $idpersonal, $idsucursal, $idtipodoc, $iddeposito,
     $fecha_hora, $obs, $monto_compra, $idmercaderia, $cantidad, $precio){


            $sql = "INSERT INTO nota_credito_debito_compra (idcompra, idproveedor, idpersonal, idsucursal, idtipodocumento, iddeposito, nro_nota_credito_debito, timbrado, fecha, obs, monto, estado) 
            VALUES ('$idcompra', '$idproveedor', '$idpersonal', '$idsucursal', '$idtipodoc', '$iddeposito', '$nronotacredito', '$timbrado', '$fecha_hora', '$obs', '$monto_compra', '1')";
            $idcreditonew = ejecutarConsulta_retornarID($sql);
    
            $num_elementos=0;
            $sw=true;
    
            while($num_elementos < count($idmercaderia)){
                $sql_detalle = "INSERT INTO nota_credi_debi_detalle (idnotacredidebi, idmercaderia, cantidad, precio) 
                VALUES ('$idcreditonew', '$idmercaderia[$num_elementos]', '$cantidad[$num_elementos]', '$precio[$num_elementos]')";
                ejecutarConsulta($sql_detalle) or $sw = false;
    
                $num_elementos =$num_elementos + 1;
            }
    
            //update en la tabla cuentas a pagar aca abajo
            $sql2 = "UPDATE cuentas_a_pagar SET idnotacredidebi = '$idcreditonew' WHERE idcompra = '$idcompra'";
            ejecutarConsulta($sql2);
            
            return $sw;

    }

    //implementamos un metodo para anular ingresos
    public function anular($idcredito, $idcompra){
        $sql = "UPDATE nota_credito_debito_compra SET estado='0' WHERE idnotacredidebi = '$idcredito'";

        // tenemos que actualizar la compra anulada de la tabla cuentas a pagar
        $sql2 = "UPDATE cuentas_a_pagar SET idnotacredidebi = '0' WHERE idcompra = '$idcompra'";
        ejecutarConsulta($sql2);

        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idcredito){
        $sql = "SELECT nc.idnotacredidebi, nc.idcompra, nc.nro_nota_credito_debito, nc.timbrado, nc.idproveedor, p.razonsocial AS proveedor, nc.idpersonal, concat(per.nombre, ' ', per.apellido) AS personal, nc.idsucursal, suc.descripcion AS sucursal, nc.idtipodocumento, td.descripcion AS tipodoc, nc.iddeposito, d.descripcion AS deposito, date(nc.fecha) as fecha, nc.obs 
        FROM nota_credito_debito_compra nc JOIN proveedores p ON nc.idproveedor = p.idproveedor JOIN personales per ON nc.idpersonal = per.idpersonal JOIN sucursales suc ON nc.idsucursal = suc.idsucursal JOIN tipo_documentos td ON nc.idtipodocumento = td.idtipodocumento JOIN depositos d ON nc.iddeposito = d.iddeposito 
        WHERE nc.idnotacredidebi = '$idcredito'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idcredito){
        $sql = "SELECT crd.idnotacredidebi, crd.idmercaderia, m.descripcion, crd.cantidad, crd.precio, ti.tipo AS iva
        FROM nota_credi_debi_detalle crd JOIN mercaderias m ON crd.idmercaderia = m.idmercaderia JOIN tipo_impuestos ti 
        ON m.idtipoimpuesto = ti.idtipoimpuesto WHERE crd.idnotacredidebi = '$idcredito'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT nc.idnotacredidebi, nc.nro_nota_credito_debito, date(nc.fecha) as fecha, nc.idcompra, p.razonsocial AS proveedor, concat(per.nombre, ' ', per.apellido) AS personal, nc.monto, nc.estado 
        FROM nota_credito_debito_compra nc JOIN proveedores p ON nc.idproveedor = p.idproveedor JOIN personales per ON nc.idpersonal = per.idpersonal 
        ORDER BY nc.idcompra DESC";
        return ejecutarConsulta($sql);
    }

}
?>