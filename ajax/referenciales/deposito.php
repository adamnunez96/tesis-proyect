<?php 
require_once "../../modelos/referenciales/Deposito.php";

$deposito = new Deposito();

$iddeposito = isset($_POST["iddeposito"])? limpiarCadena($_POST["iddeposito"]): "";
$idsucursal = isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($iddeposito)){
            $existencia = $deposito->validarExistencia($descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $deposito->insertar($idsucursal, $descripcion);
                echo $rspta ? "Deposito registrado" : "Deposito no se pudo registrar";
            }
        }else{
            $existencia = $deposito->validarExistencia($descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $deposito->editar($iddeposito, $idsucursal, $descripcion);
                echo $rspta ? "Deposito actualizado" : "Deposito no se pudo actualizar";
            }
        }
    break;

    case 'eliminar':
        $rspta = $deposito->eliminar($iddeposito);
            echo $rspta ? "Deposito desactivado" : "Deposito no se pudo desactivado";
    break;

    case 'activar':
        $rspta = $deposito->activar($iddeposito);
        echo $rspta ? "Deposito Activado" : "Deposito no se pudo Activar";
    break;

    case 'mostrar':
        $rspta=$deposito->mostrar($iddeposito);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$deposito->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->iddeposito,
                "1"=>$reg->descripcion,
                "2"=>$reg->sucursal,
                "3"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "4"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->iddeposito. ')"><i class="fa fa-pencil"></i></button>'. 
                ' <button class="btn btn-danger" onclick="eliminar(' .$reg->iddeposito. ')"><i class="fa fa-trash"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->iddeposito. ')"><i class="fa fa-pencil"></i></button>'. 
                ' <button class="btn btn-primary" onclick="activar(' .$reg->iddeposito. ')"><i class="fa fa-check"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
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