<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class TipoDocumento {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($descripcion){
        $sql = "INSERT into tipo_documentos (descripcion) values ('$descripcion')";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar($idtipodocumento, $descripcion){
        $sql = "UPDATE tipo_documentos set descripcion = '$descripcion' where idtipodocumento = '$idtipodocumento'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para eliminar registros
    public function eliminar($idtipodocumento){
        $sql = "DELETE from tipo_documentos where idtipodocumento = '$idtipodocumento'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idtipodocumento){
        $sql = "SELECT * from tipo_documentos where idtipodocumento = '$idtipodocumento'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from tipo_documentos";
        return ejecutarConsulta($sql);
    }

    public function validarExistencia($descripcion){
        $sql = "SELECT * from tipo_documentos where descripcion = '$descripcion'";
        $resul = ejecutarConsulta($sql);
        return mysqli_num_rows($resul);
    }

}
?>