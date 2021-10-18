<?php 
require_once "../../modelos/referenciales/Personal.php";

$personal = new Personal();

$idpersonal = isset($_POST["idpersonal"])? limpiarCadena($_POST["idpersonal"]): "";
$idusuario = isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]): "";
$idciudad = isset($_POST["idciudad"])? limpiarCadena($_POST["idciudad"]): "";
$idsucursal = isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]): "";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]): "";
$apellido = isset($_POST["apellido"])? limpiarCadena($_POST["apellido"]): "";
$documento = isset($_POST["documento"])? limpiarCadena($_POST["documento"]): "";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]): "";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]): "";
$cargo = isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]): "";
$correo = isset($_POST["correo"])? limpiarCadena($_POST["correo"]): "";
$imagen = isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':

        if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])){
            $imagen = $_POST["imagenactual"];
        }else{
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png"){

                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../../files/usuarios/" . $imagen);
            }
        }

        if(empty($idpersonal)){
            $existencia = $personal->validarExistencia($idpersonal, $nombre, $apellido, $documento);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $personal->insertar($idusuario, $idciudad, $idsucursal, $nombre, $apellido, $documento, $direccion, $telefono, $cargo, $correo, $imagen);
                echo $rspta ? "Personal registrado" : "Personal no se pudo registrar";
            }
        }else{
            $existencia = $personal->validarExistencia($idpersonal, $nombre, $apellido, $documento);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $personal->editar($idpersonal, $idusuario, $idciudad, $idsucursal, $nombre, $apellido, $documento, $direccion, $telefono, $cargo, $correo, $imagen);
                echo $rspta ? "Personal actualizado" : "Personal no se pudo actualizar";
            }
        }
    break;
// 
    case 'desactivar':
        $rspta = $personal->desactivar($idpersonal);
            echo $rspta ? "Personal desactivado" : "Personal no se pudo desactivar";
    break;

    case 'activar':
        $rspta = $personal->activar($idpersonal);
            echo $rspta ? "Personal activado" : "Personal no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$personal->mostrar($idpersonal);
        //decodificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$personal->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idpersonal,
                "1"=>$reg->nombre,
                "2"=>$reg->apellido,
                "3"=>$reg->usuario,
                "4"=>$reg->cargo,
                "5"=>$reg->telefono,
                "6"=>"<img src='../../files/usuarios/".$reg->imagen."' height='50px' width='50px'>",
                "7"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "8"=>($reg->estado)?'<button class="btn btn-success" onclick="mostrar2(' .$reg->idpersonal. ')"><i class="fa fa-eye"></i></button>' .' <button class="btn btn-warning" onclick="mostrar(' .$reg->idpersonal. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="desactivar(' .$reg->idpersonal. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-success" onclick="mostrar2(' .$reg->idpersonal. ')"><i class="fa fa-eye"></i></button>'. ' <button class="btn btn-warning" onclick="mostrar(' .$reg->idpersonal. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-primary" onclick="activar(' .$reg->idpersonal. ')"><i class="fa fa-check"></i></button>',
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case "selectUsuario": //Este debe ser un select que debe de mostrar los usuarios

        require_once "../../modelos/referenciales/Usuario.php";

        $usuario = new Usuario();
        $rspta = $usuario->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idusuario. '>' . $reg->usuario . '</option>';
        }

    break;

    case "selectCiudad": //Este debe ser un select que debe de mostrar las ciudades

        require_once "../../modelos/referenciales/Ciudad.php";

        $ciudad = new Ciudad();
        $rspta = $ciudad->listarActivos();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idciudad. '>' . $reg->descripcion . '</option>';
        }

    break;

    case "selectSucursal": //Este debe ser un select que debe de mostrar las ciudades

        require_once "../../modelos/referenciales/Sucursal.php";

        $sucursal = new Sucursal();
        $rspta = $sucursal->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idsucursal. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>