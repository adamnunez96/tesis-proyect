<?php 
require_once "../../modelos/referenciales/Cliente.php";

$cliente = new Cliente();

$idcliente = isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]): "";
$idciudad = isset($_POST["idciudad"])? limpiarCadena($_POST["idciudad"]): "";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]): "";
$apellido = isset($_POST["apellido"])? limpiarCadena($_POST["apellido"]): "";
$ci = isset($_POST["ci"])? limpiarCadena($_POST["ci"]): "";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]): "";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]): "";
$correo = isset($_POST["correo"])? limpiarCadena($_POST["correo"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idcliente)){
            $existencia = $cliente->validarExistencia($idcliente, $ci, $nombre, $apellido);
            if($existencia > 0){
                echo "El Cliente ".$nombre ." ". $apellido." con CI ".$ci." ya se encuentra registrado. Favor verificar";
            }else{
                $rspta = $cliente->insertar($idciudad, $ci, $nombre, $apellido, $direccion, $telefono, $correo);
                echo $rspta ? "Cliente registrado" : "Cliente no se pudo registrar";
            }
        }else{
            $existencia = $cliente->validarExistencia($idcliente, $ci, $nombre, $apellido);
            if($existencia > 0){
                echo "El Cliente ".$nombre + $apellido." con CI ".$ci." ya se encuentra registrado. Favor verificar";
            }else{
                $rspta = $cliente->editar($idcliente, $idciudad, $ci, $nombre, $apellido, $direccion, $telefono, $correo);
                echo $rspta ? "Cliente actualizado" : "Cliente no se pudo actualizar";
            }
        }
    break;

    case 'desactivar':
        $rspta = $cliente->desactivar($idcliente);
            echo $rspta ? "Cliente desactivado" : "Cliente no se pudo desactivar";
    break;

    case 'activar':
        $rspta = $cliente->activar($idcliente);
            echo $rspta ? "Cliente activado" : "Cliente no se pudo activar";
    break;

    case 'mostrar':
        $rspta= $cliente->mostrar($idcliente);
        //decodificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$cliente->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idcliente,
                "1"=>$reg->nombre,
                "2"=>$reg->apellido,
                "3"=>$reg->ci,
                "4"=>$reg->telefono,
                "5"=>$reg->ciudad,
                "6"=>$reg->direccion,
                "7"=>$reg->correo,
                "8"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "9"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idcliente. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="desactivar(' .$reg->idcliente. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idcliente. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-primary" onclick="activar(' .$reg->idcliente. ')"><i class="fa fa-check"></i></button>',
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case "selectCiudad": //Este debe ser un select que debe de mostrar las ciudades

        require_once "../../modelos/referenciales/Ciudad.php";

        $ciudad = new Ciudad();
        $rspta = $ciudad->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idciudad. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>