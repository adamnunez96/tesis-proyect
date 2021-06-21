<?php 
require_once "../../modelos/referenciales/TipoImpuesto.php";

$tipoImpuesto = new TipoImpuesto();

$idTipoImpuesto = isset($_POST["idTipoImpuesto"])? limpiarCadena($_POST["idTipoImpuesto"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";
$tipo = isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idTipoImpuesto)){
            $rspta = $tipoImpuesto->insertar($descripcion, $tipo);
            echo $rspta ? "Tipo de Impuesto registrado" : "Tipo de Impuesto no se pudo registrar";
        }else{
            $rspta = $tipoImpuesto->editar($idTipoImpuesto, $descripcion, $tipo);
            echo $rspta ? "Tipo de Impuesto actualizado" : "Tipo de Impuesto no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta = $tipoImpuesto->eliminar($idTipoImpuesto);
            echo $rspta ? "Tipo de Impuesto eliminado" : "Tipo de Impuesto no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta=$tipoImpuesto->mostrar($idTipoImpuesto);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$tipoImpuesto->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idtipoimpuesto,
                "1"=>$reg->descripcion,
                "2"=>$reg->tipo,
                "3"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->idtipoimpuesto. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idtipoimpuesto. ')"><i class="fa fa-trash"></i></button>'
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