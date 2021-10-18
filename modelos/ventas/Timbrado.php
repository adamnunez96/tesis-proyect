<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Timbrado {
    //implementamos nuestro constructor
    public function __construct(){

    }

    public function insertar($idtipodoc, $nrotimbrado, $nrodesde, $nrohasta, $fechainicio, $fechafin, $serie1, $serie2){

        $sql = "INSERT INTO timbrados (idtipodocumento, nrotimbrado, nrodesde, nrohasta, fechainicio, fechafin, nro1, nro2, estado) 
        VALUES ('$idtipodoc', '$nrotimbrado', '$nrodesde', '$nrohasta', '$fechainicio', '$fechafin', '$serie1', '$serie2', '1')";

        return ejecutarConsulta($sql);
    }
    
    public function listar(){
        $sql = "SELECT * from timbrados";
        return ejecutarConsulta($sql);
    }

    public function actualizar($idtimbrado, $ultimonro){
        $sql = "UPDATE timbrados set nroactual = '$ultimonro' where idtimbrado = '$idtimbrado'";
        return ejecutarConsulta($sql);
    }

    public function recuperarTimbrado(){
        $sql = "SELECT nrotimbrado, (SELECT MAX(nroactual)+1) as nrofactura, nro1, nro2 FROM timbrados WHERE estado = '1' and idtipodocumento = '2'";
        return ejecutarConsulta($sql);
    }

}
?>