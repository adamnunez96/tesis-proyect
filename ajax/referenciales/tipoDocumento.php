<?php 
require_once "../../modelos/referenciales/TipoDocumento.php";

$tipoDocumento = new TipoDocumento();

$idtipodocumento = isset($_POST["idtipodocumento"])? limpiarCadena($_POST["idtipodocumento"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idtipodocumento)){
            $rspta = $tipoDocumento->insertar($descripcion);
            echo $rspta ? "Tipo Documento registrado" : "Tipo Documento no se pudo registrar";
        }else{
            $rspta = $tipoDocumento->editar($idtipodocumento, $descripcion);
            echo $rspta ? "Tipo Documento actualizado" : "Tipo Documento no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta = $tipoDocumento->eliminar($idtipodocumento);
            echo $rspta ? "Tipo Documento eliminado" : "Tipo Documento no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta = $tipoDocumento->mostrar($idtipodocumento);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$tipoDocumento->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idtipodocumento,
                "1"=>$reg->descripcion,
                "2"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->idtipodocumento. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idtipodocumento. ')"><i class="fa fa-trash"></i></button>'
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