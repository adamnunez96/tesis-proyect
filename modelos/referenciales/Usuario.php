<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class Usuario {
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros
    public function insertar($usuario, $clave, $permisos){
        $sql = "INSERT into usuarios (usuario, clave, estado) 
        values ('$usuario', '$clave', '1')";
        //return ejecutarConsulta($sql);
        $idusuarionew = ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while($num_elementos < count($permisos)){

            $sql_detalle = "INSERT INTO usuariopermiso(idusuario, idpermiso) VALUES ('$idusuarionew', '$permisos[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos =$num_elementos + 1;
        }

        return $sw;
    }

    //implementamos un metodo para editar registros
    public function editar($idusuario, $usuario, $clave, $permisos){
        if(strlen($clave)>0){
            $sql = "UPDATE usuarios set usuario = '$usuario', clave = '$clave' where idusuario = '$idusuario'";
            ejecutarConsulta($sql);
        }else{ //ya no actualiza el campo clave
            $sql = "UPDATE usuarios set usuario = '$usuario' where idusuario = '$idusuario'";
            ejecutarConsulta($sql);
        }
        
        //eliminamos todos los permisos asignados paraa volverlos a registrar
        $sqldel="DELETE FROM usuariopermiso WHERE idusuario = $idusuario";
        ejecutarConsulta($sqldel);

        $num_elementos=0;
        $sw=true;

        while($num_elementos < count($permisos)){

            $sql_detalle = "INSERT INTO usuariopermiso(idusuario, idpermiso) VALUES ('$idusuario', '$permisos[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            $num_elementos =$num_elementos + 1;
        }

        return $sw;

    }

    //implementamos un metodo para desactivar usuarios
    public function desactivar($idusuario){
        $sql = "UPDATE usuarios set estado='0' where idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para activar usuarios
    public function activar($idusuario){
        $sql = "UPDATE usuarios set estado='1' where idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idusuario){
        $sql = "SELECT * from usuarios where idusuario = '$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //implementar un metodo para listar los registros
    public function listar(){
        $sql = "SELECT * from usuarios";
        return ejecutarConsulta($sql);
    }

    //implementar un metodo para listar los permisos marcados
    public function listarmarcados($idusuario){
        $sql="SELECT * FROM usuariopermiso WHERE idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

    //funcion para verificar el acceso al sistema
    public function verificar($login, $clave, $sucursal){
        $sql="SELECT p.idusuario, u.usuario, p.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, p.cargo, p.imagen, s.descripcion as sucu 
        FROM personales p JOIN usuarios u on p.idusuario = u.idusuario JOIN sucursales s ON p.idsucursal = s.idsucursal 
        where u.usuario = '$login' and u.clave = '$clave' and u.estado = '1' and p.idsucursal = '$sucursal'";
        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para inactivar los el usuario al alcanzar 3 intentos fallidos en el login
    public function inactivar($logina){
        $sql = "UPDATE usuarios set estado='0' where usuario = '$logina'";
        return ejecutarConsulta($sql);
    }

}
?>