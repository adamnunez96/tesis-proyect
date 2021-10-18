<?php 
require_once "../../modelos/referenciales/Proveedor.php";

$proveedor = new Proveedor();

$idproveedor = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]): "";
$idciudad = isset($_POST["idciudad"])? limpiarCadena($_POST["idciudad"]): "";
$razonSocial = isset($_POST["razonsocial"])? limpiarCadena($_POST["razonsocial"]): "";
$ruc = isset($_POST["ruc"])? limpiarCadena($_POST["ruc"]): "";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]): "";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]): "";
$correo = isset($_POST["correo"])? limpiarCadena($_POST["correo"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idproveedor)){
            $existencia = $proveedor->validarExistencia($idproveedor, $razonSocial, $ruc);
            if($existencia > 0){
                echo "Ya existe un Proveedor con estos datos. Favor verificar";
            }else{
                $rspta = $proveedor->insertar($idciudad, $razonSocial, $ruc, $direccion, $telefono, $correo);
                echo $rspta ? "Proveedor registrado" : "Proveedor no se pudo registrar";
            }
        }else{
            $existencia = $proveedor->validarExistencia($idproveedor, $razonSocial, $ruc);
            if($existencia > 0){
                echo "Ya existe un Proveedor con estos datos. Favor verificar";
            }else{
                $rspta = $proveedor->editar($idproveedor, $idciudad, $razonSocial, $ruc, $direccion, $telefono, $correo);
                echo $rspta ? "Deposito actualizado" : "Deposito no se pudo actualizar";
            }
        }
    break;

    case 'desactivar':
        $rspta = $proveedor->desactivar($idproveedor);
            echo $rspta ? "Proveedor desactivado" : "Proveedor no se pudo desactivar";
    break;

    case 'activar':
        $rspta = $proveedor->activar($idproveedor);
            echo $rspta ? "Proveedor activado" : "Proveedor no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$proveedor->mostrar($idproveedor);
        //decodificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$proveedor->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idproveedor,
                "1"=>$reg->razonsocial,
                "2"=>$reg->ruc,
                "3"=>$reg->ciudad,
                "4"=>$reg->direccion,
                "5"=>$reg->telefono,
                "6"=>$reg->correo,
                "7"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "8"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idproveedor. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="desactivar(' .$reg->idproveedor. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idproveedor. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-primary" onclick="activar(' .$reg->idproveedor. ')"><i class="fa fa-check"></i></button>',
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
        $rspta = $ciudad->listarActivos();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idciudad. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>