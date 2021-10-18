<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class NotaRemision {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idsucursal, $iddeposito, $idperEnvia, $idperRecibe, $fecha_hora, $idmercaderia, $cantidad, $precio){

        $sql = "INSERT INTO nota_remision (idsucursal, iddeposito, idpersonalenvia, idpersonalrecibe, fecha, estado) 
        VALUES ('$idsucursal', '$iddeposito', '$idperEnvia', '$idperRecibe', '$fecha_hora', '1')";
        
        $idnota = ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while($num_elementos < count($idmercaderia)){

            $sql_detalle = "INSERT INTO nota_remision_detalle (idnotaremision, idmercaderia, cantidad, precio) 
            VALUES ('$idnota', '$idmercaderia[$num_elementos]', '$cantidad[$num_elementos]', '$precio[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos =$num_elementos + 1;
        }

        return $sw;

    }

    //implementamos un metodo para anular la nota de remision
    public function anular($idnota){
        $sql = "UPDATE nota_remision SET estado='0' WHERE idnotaremision = '$idnota'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro
    public function mostrar($idnota){
        $sql = "SELECT n.idnotaremision, n.idsucursal, s.descripcion AS sucursal, n.iddeposito, d.descripcion AS deposito, n.idpersonalenvia, 
        concat(p.nombre ,' ', p.apellido) AS perenvia, n.idpersonalrecibe, concat(ps.nombre ,' ', ps.apellido) AS perrecibe, replace(n.fecha, ' ', 'T') as fecha
        FROM nota_remision n JOIN sucursales s ON n.idsucursal = s.idsucursal JOIN depositos d ON n.iddeposito = d.iddeposito 
        JOIN personales p ON n.idpersonalenvia = p.idpersonal JOIN personales ps ON n.idpersonalrecibe = ps.idpersonal 
        WHERE n.idnotaremision = '$idnota'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idnota){
        $sql = "SELECT nd.idnotaremision, nd.idmercaderia, m.descripcion, nd.cantidad, nd.precio 
        FROM nota_remision_detalle nd JOIN mercaderias m ON nd.idmercaderia = m.idmercaderia 
        WHERE nd.idnotaremision = '$idnota'";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los registros
    public function listar($idsucursal){
        $sql = "SELECT n.idnotaremision, date(n.fecha) as fecha, n.idsucursal, s.descripcion AS sucursal, n.iddeposito, 
        d.descripcion as deposito, n.idpersonalrecibe, concat(p.nombre ,' ', p.apellido) AS perrecibe, n.estado 
        FROM nota_remision n JOIN sucursales s ON n.idsucursal = s.idsucursal JOIN personales p ON n.idpersonalrecibe = p.idpersonal 
        JOIN depositos d ON n.iddeposito = d.iddeposito WHERE n.idsucursal = '$idsucursal' ORDER BY n.idnotaremision DESC";
        return ejecutarConsulta($sql);
    }

}
?>