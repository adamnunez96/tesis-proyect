<?php 
session_start();
require_once "../../modelos/referenciales/Usuario.php";

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]): "";
$login = isset($_POST["usuario"])? limpiarCadena($_POST["usuario"]): "";
$clave = isset($_POST["clave"])? limpiarCadena($_POST["clave"]): "";
if (isset($_POST['permiso'])) {
    $permisos = $_POST['permiso'];
} else {
    $permisos = array();
}

switch ($_GET["op"]){
    case 'guardaryeditar':
       
        //Hash SHA256 en la contrasenha
        $clavehash = hash("SHA256", $clave);//Al editar se encripta n veces.

        if(empty($idusuario)){
            $rspta = $usuario->insertar($login, $clavehash, $permisos);
            echo $rspta ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
        }else{
            $rspta = $usuario->editar($idusuario, $login, $clavehash, $permisos);
            echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
        }

    break;

    case 'desactivar':
        $rspta = $usuario->desactivar($idusuario);
            echo $rspta ? "Usuario Desactivado" : "Usuario no se puede Desactivar";
    break;

    case 'activar':
        $rspta = $usuario->activar($idusuario);
            echo $rspta ? "Usuario Activado" : "Usuario no se puede Activar";
    break;

    case 'mostrar':
        $rspta=$usuario->mostrar($idusuario);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$usuario->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idusuario,
                "1"=>$reg->usuario,
                "2"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "3"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idusuario. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="desactivar(' .$reg->idusuario. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idusuario. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-primary" onclick="activar(' .$reg->idusuario. ')"><i class="fa fa-check"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case 'permisos':
        //obtenemos todos los permisos de la tabla permisos
        require_once "../../modelos/referenciales/Permiso.php";
        $permiso = new Permiso();
        $rspta = $permiso->listar();

        //obtener los permisos asignados al usuario\
        $id = $_GET['id'];
        $marcados = $usuario->listarmarcados($id);
        //declaramos el array para almacenar todos los permisos marcados
        $valores=array();

        //almacenar los permisos asignados al usuario en el array
        while ($per = $marcados->fetch_object()){
            array_push($valores, $per->idpermiso);
        }

        //mostramos la lista de permisos en la vista y si estan o no marcados
        
        while($reg = $rspta->fetch_object()){
            $sw=in_array($reg->idpermiso, $valores)?'checked':'';
            echo '<li><input type="checkbox" '.$sw.' name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->descripcion.'</li>';
        }

    break;

    case 'verificar':
        $logina = $_POST['logina'];
        $clavea = $_POST['clavea'];
        $sucursal = $_POST['sucursal'];

        // Hash SHA256 en la contrasena
        $clavehash = hash("SHA256", $clavea);

        $rspta = $usuario->verificar($logina, $clavehash, $sucursal);

        $fetch = $rspta->fetch_object();

        if (isset($fetch)){ 
                //declaramos las variables de sesion
            $_SESSION['idusuario']=$fetch->idusuario;
            $_SESSION['idpersonal']=$fetch->idpersonal;
            $_SESSION['personal']=$fetch->personal;
            $_SESSION['imagen']=$fetch->imagen;
            $_SESSION['usuario']=$fetch->usuario;
            $_SESSION['idsucursal']=$sucursal;
            $_SESSION['sucursal']=$fetch->sucu; //esto es la sucursal

            //obetenemos los permisos del usuario
            $marcados = $usuario->listarmarcados($fetch->idusuario);

            //declaramos el array para almacenar todos los permisos
            $valores=array();
            while($per = $marcados->fetch_object()){
                array_push($valores, $per->idpermiso);
            }

            //determinamos los accesos del usuario
            in_array(1,$valores)?$_SESSION['REFERENCIALES']=1:$_SESSION['REFERENCIALES']=0;
            in_array(2,$valores)?$_SESSION['COMPRAS']=1:$_SESSION['COMPRAS']=0;
            in_array(3,$valores)?$_SESSION['VENTAS']=1:$_SESSION['VENTAS']=0;
            in_array(4,$valores)?$_SESSION['SERVICIOS']=1:$_SESSION['SERVICIOS']=0;
            in_array(5,$valores)?$_SESSION['REPORTES']=1:$_SESSION['REPORTES']=0;
            in_array(6,$valores)?$_SESSION['ESCRITORIO']=1:$_SESSION['ESCRITORIO']=0;
            in_array(7,$valores)?$_SESSION['ADMINISTRADOR']=1:$_SESSION['ADMINISTRADOR']=0;

        }
        echo json_encode($fetch); 

    break;

    case 'inactivar':
        $logina = $_POST['logina'];
        $rspta = $usuario->inactivar($logina);
            echo $rspta ? "Usuario bloqueado por intentos fallidos, contacte con el Administrador" : "Usuario no se puede bloquear";
    break;

    case 'salir':
        // limpiamos las variables de sesion
        session_unset();     
        // destruimos la sesion
        session_destroy();
        header("Location: ../../index.php");
    break;
}

?>