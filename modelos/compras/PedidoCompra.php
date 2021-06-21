<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class PedidoCompra {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idsucursal, $idpersonal, $fecha_hora, $observacion, $idmercaderia, $cantidad){

        $sql = "INSERT INTO pedido_compra (idsucursal, idpersonal, fecha, obs, estado) 
        VALUES ('$idsucursal', '$idpersonal', '$fecha_hora', '$observacion', '1')";
        //return ejecutarConsulta($sql);
        $idpedidonew = ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while($num_elementos < count($idmercaderia)){

            $sql_detalle = "INSERT INTO pedido_detalle (idpedido, idmercaderia, cantidad) VALUES ('$idpedidonew', '$idmercaderia[$num_elementos]', '$cantidad[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos =$num_elementos + 1;
        }

        return $sw;
    }

    //implementamos un metodo para anular ingresos
    public function anular($idpedido){
        $sql = "UPDATE pedido_compra SET estado='0' WHERE idpedido = '$idpedido'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idpedido){
        $sql = "SELECT pc.idpedido, pc.idsucursal, s.descripcion AS sucursal, pc.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, replace(pc.fecha, ' ', 'T') AS fecha, pc.obs, pc.estado 
        FROM pedido_compra pc JOIN sucursales s ON pc.idsucursal = s.idsucursal JOIN personales p ON pc.idpersonal = p.idpersonal 
        WHERE idpedido = '$idpedido'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idpedido){
        $sql = "SELECT pd.idpedido, pd.idmercaderia, m.descripcion, pd.cantidad, m.preciocompra 
        FROM pedido_detalle pd JOIN mercaderias m ON pd.idmercaderia = m.idmercaderia WHERE idpedido = '$idpedido'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT pc.idpedido, date(pc.fecha) AS fecha, concat(p.nombre, ' ', p.apellido) AS personal, s.descripcion AS sucursal, pc.estado 
        FROM pedido_compra pc JOIN personales p ON pc.idpersonal = p.idpersonal JOIN sucursales s ON pc.idsucursal = s.idsucursal 
        ORDER BY pc.idpedido DESC";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros 
    public function listarActivos(){
        $sql = "SELECT pc.idpedido, date(pc.fecha) AS fecha, concat(p.nombre, ' ', p.apellido) AS personal, s.descripcion AS sucursal, pc.estado 
        FROM pedido_compra pc JOIN personales p ON pc.idpersonal = p.idpersonal JOIN sucursales s ON pc.idsucursal = s.idsucursal 
        WHERE pc.estado = '1' ORDER BY pc.idpedido DESC";
        return ejecutarConsulta($sql);
    }

}
?>