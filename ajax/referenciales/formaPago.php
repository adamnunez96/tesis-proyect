<?php 
require_once "../../modelos/referenciales/FormaPago.php";

$formaPago = new FormaPago();

$idformapago = isset($_POST["idformapago"])? limpiarCadena($_POST["idformapago"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idformapago)){
            $existencia = $formaPago->validarExistencia($descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $formaPago->insertar($descripcion, $_POST["cuota"]);
                echo $rspta ? "Forma de Pago registrado" : "Forma de Pago no se pudo registrar";
            }
        }else{
            $existencia = $formaPago->validarExistencia($descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $formaPago->editar($idformapago, $descripcion, $_POST["cuota"]);
                echo $rspta ? "Forma de Pago actualizado" : "Forma de Pago no se pudo actualizar";
            }
        }
    break;

    case 'eliminar':
        $rspta = $formaPago->eliminar($idformapago);
            echo $rspta ? "Forma de Pago eliminado" : "Forma de Pago no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta=$formaPago->mostrar($idformapago);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$formaPago->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idformapago,
                "1"=>$reg->descripcion,
                "2"=>$reg->cuota,
                "3"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->idformapago. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idformapago. ')"><i class="fa fa-trash"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

}

?>