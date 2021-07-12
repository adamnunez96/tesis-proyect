<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class AjusteStock{
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idsucursal, $idpersonal, $iddeposito, $fecha_hora, $obs, $tipoajuste, $idmercaderia, $cantidad, $precio){

        $sql = "INSERT INTO ajustes (idsucursal, idpersonal, iddeposito, fecha, obs, tipoajuste, estado) 
        VALUES ('$idsucursal', '$idpersonal', '$iddeposito', '$fecha_hora', '$obs', '$tipoajuste', '1')";
        

        $idajustenew = ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while($num_elementos < count($idmercaderia)){

            $sql_detalle = "INSERT INTO ajustes_detalle (idajuste, idmercaderia, cantidad, precio) 
            VALUES ('$idajustenew', '$idmercaderia[$num_elementos]', '$cantidad[$num_elementos]', '$precio[$num_elementos]')";

            ejecutarConsulta($sql_detalle) or $sw = false;

            if($tipoajuste == 1){
                $sql2 = "UPDATE stock SET cantidad = cantidad + '$cantidad[$num_elementos]' WHERE idmercaderia = '$idmercaderia[$num_elementos]' AND iddeposito = '$iddeposito'";
                //print_r($sql2);
                ejecutarConsulta($sql2);
            }else{
                $sql2 = "UPDATE stock SET cantidad = cantidad - '$cantidad[$num_elementos]' WHERE idmercaderia = '$idmercaderia[$num_elementos]' AND iddeposito = '$iddeposito'";
                //print_r($sql2);
                ejecutarConsulta($sql2);
            };

            $num_elementos =$num_elementos + 1;
            
        }

        return $sw;
    }

    //implementamos un metodo para editar o modificar los ajustes
    public function editar($idajuste, $idpersonal, $iddeposito, $obs, $tipoajuste, $idmercaderia, $cantidad, $precio){

        $sql_ajuste = "UPDATE ajustes SET idpersonal = '$idpersonal', obs = '$obs' WHERE idajuste = '$idajuste'";
        ejecutarConsulta($sql_ajuste);

        $num_elementos = 0;
        $response = true;

        while($num_elementos < count($idmercaderia)){
        
            $sql_ajuste_detalle = "UPDATE ajustes_detalle SET cantidad = '$cantidad[$num_elementos]', precio = '$precio[$num_elementos]' WHERE idajuste = '$idajuste'";
            ejecutarConsulta($sql_ajuste_detalle) or $response = false;

            if($tipoajuste == 1){
                $sql2 = "UPDATE stock SET cantidad = cantidad + '$cantidad[$num_elementos]' WHERE idmercaderia = '$idmercaderia[$num_elementos]' AND iddeposito = '$iddeposito'";
                ejecutarConsulta($sql2);
            }else{
                $sql2 = "UPDATE stock SET cantidad = cantidad - '$cantidad[$num_elementos]' WHERE idmercaderia = '$idmercaderia[$num_elementos]' AND iddeposito = '$iddeposito'";
                ejecutarConsulta($sql2);
            };

            $num_elementos = $num_elementos + 1;
        }

        return $response;

    }

    //implementamos un metodo para anular ingresos
    public function anular($idajuste, $tipoajuste){

        $sql = "UPDATE ajustes SET estado='0' WHERE idajuste = '$idajuste'";

        $rs = ejecutarConsulta($sql);

        if($tipoajuste == 1){
            $sql2 = "UPDATE stock s JOIN ajustes_detalle ad ON s.idmercaderia = ad.idmercaderia AND ad.idajuste = '$idajuste' 
            SET s.cantidad = s.cantidad - ad.cantidad";
            ejecutarConsulta($sql2);
        }else{
            $sql2 = "UPDATE stock s JOIN ajustes_detalle ad ON s.idmercaderia = ad.idmercaderia AND ad.idajuste = '$idajuste' 
            SET s.cantidad = s.cantidad + ad.cantidad";
            ejecutarConsulta($sql2);
        }

        return $rs;
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idajuste){
        $sql = "SELECT a.idajuste, a.idsucursal, s.descripcion AS sucursal, a.iddeposito, d.descripcion AS deposito, a.idpersonal, 
        concat(p.nombre, ' ', p.apellido) AS personal, replace(a.fecha, ' ', 'T') AS fecha, a.obs, a.tipoajuste 
        FROM ajustes a JOIN sucursales s on a.idsucursal = s.idsucursal JOIN depositos d on a.iddeposito = d.iddeposito JOIN personales p ON a.idpersonal = p.idpersonal 
        WHERE a.idajuste = '$idajuste'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idajuste){
        $sql = "SELECT ad.idajuste, ad.idmercaderia, m.descripcion, ad.cantidad, ad.precio 
        FROM ajustes_detalle ad JOIN mercaderias m ON ad.idmercaderia = m.idmercaderia WHERE ad.idajuste = '$idajuste'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT a.idajuste, date(a.fecha) AS fecha, concat(p.nombre, ' ', p.apellido) AS personal, s.descripcion AS sucursal, a.tipoajuste, a.estado 
        FROM ajustes a JOIN personales p ON a.idpersonal = p.idpersonal JOIN sucursales s ON a.idsucursal = s.idsucursal JOIN depositos d ON a.iddeposito = d.iddeposito
        ORDER BY a.idajuste DESC";
        return ejecutarConsulta($sql);
    }



    //implementar un metodo para listar los registros 
    // public function listarActivos(){
    //     $sql = "SELECT pc.idpedido, date(pc.fecha) AS fecha, concat(p.nombre, ' ', p.apellido) AS personal, s.descripcion AS sucursal, pc.estado 
    //     FROM pedido_compra pc JOIN personales p ON pc.idpersonal = p.idpersonal JOIN sucursales s ON pc.idsucursal = s.idsucursal 
    //     WHERE pc.estado = '1' ORDER BY pc.idpedido DESC";
    //     return ejecutarConsulta($sql);
    //}

}
?>