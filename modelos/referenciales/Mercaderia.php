<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Mercaderia {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($idtipoimpuesto, $idmarca, $descripcion, $precioCompra, $precioVenta, $imagen){
        $sql = "INSERT INTO mercaderias (idtipoimpuesto, idmarca, descripcion, preciocompra, precioventa, imagen, estado) values ('$idtipoimpuesto', '$idmarca', '$descripcion', '$precioCompra', '$precioVenta', '$imagen', '1')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idmercaderia, $idtipoimpuesto, $idmarca, $descripcion, $precioCompra, $precioVenta, $imagen){
        $sql = "UPDATE mercaderias set idtipoimpuesto = '$idtipoimpuesto', idmarca = '$idmarca', descripcion = '$descripcion', preciocompra = '$precioCompra', precioventa = '$precioVenta', imagen ='$imagen' WHERE idmercaderia = '$idmercaderia'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para desactivar registros
    public function desactivar($idmercaderia){
        $sql = "UPDATE mercaderias SET estado ='0' WHERE idmercaderia = '$idmercaderia'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para activar registros
    public function activar($idmercaderia){
        $sql = "UPDATE mercaderias SET estado ='1' WHERE idmercaderia = '$idmercaderia'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idmercaderia){
        $sql = "SELECT * FROM mercaderias where idmercaderia = '$idmercaderia'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT mer.idmercaderia, mer.descripcion, mar.descripcion as marca, mer.preciocompra, mer.precioventa, ti.descripcion as impuesto, mer.imagen, mer.estado 
        FROM mercaderias mer JOIN marcas mar ON mer.idmarca = mar.idmarca JOIN tipo_impuestos ti ON mer.idtipoimpuesto = ti.idtipoimpuesto";
        return ejecutarConsulta($sql);
    }

    public function listarActivos(){
        $sql = "SELECT mer.idmercaderia, mer.descripcion, mar.descripcion as marca, mer.preciocompra, mer.precioventa, ti.descripcion as impuesto, ti.tipo, mer.imagen, mer.estado 
        FROM mercaderias mer JOIN marcas mar ON mer.idmarca = mar.idmarca JOIN tipo_impuestos ti ON mer.idtipoimpuesto = ti.idtipoimpuesto 
        WHERE mer.estado = '1'";
        return ejecutarConsulta($sql);
    }

}
?>