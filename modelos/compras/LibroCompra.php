<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class LibroCompra {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementar un metodo para listar los registros
    public function listar($fecha_inicio, $fecha_fin){
        $sql = "SELECT lc.idlibrocompra, c.nrofactura, date(lc.fecha) as fecha, p.razonsocial AS proveedor, lc.montopagado, lc.montoiva5, lc.grabiva5, 
        lc.montoiva10, lc.grabiva10, lc.montoexenta 
        FROM libro_compras lc JOIN compras c ON lc.idcompra = c.idcompra JOIN proveedores p ON lc.idproveedor = p.idproveedor 
        WHERE DATE(lc.fecha)>= '$fecha_inicio' AND DATE(lc.fecha)<= '$fecha_fin'
        ORDER BY lc.idlibrocompra DESC";
        return ejecutarConsulta($sql);
    }

}
?>