<?php 
require_once "../../modelos/referenciales/EntidadEmisora.php";

$entidadEmisora = new EntidadEmisora();

$idEntidadEmisora = isset($_POST["identidademisora"])? limpiarCadena($_POST["identidademisora"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]): "";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idEntidadEmisora)){
            $rspta = $entidadEmisora->insertar($descripcion, $telefono, $direccion);
            echo $rspta ? "Entidad Emisora registrada" : "Entidad Emisora no se pudo registrar";
        }else{
            $rspta = $entidadEmisora->editar($idEntidadEmisora, $descripcion, $telefono, $direccion);
            echo $rspta ? "Entidad Emisora actualizada" : "Entidad Emisora no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta = $entidadEmisora->eliminar($idEntidadEmisora);
            echo $rspta ? "Entidad Emisora eliminada" : "Entidad Emisora no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta=$entidadEmisora->mostrar($idEntidadEmisora);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$entidadEmisora->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->identidademisora,
                "1"=>$reg->descripcion,
                "2"=>$reg->telefono,
                "3"=>$reg->direccion,
                "4"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->identidademisora. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->identidademisora. ')"><i class="fa fa-trash"></i></button>'
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