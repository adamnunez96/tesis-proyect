<?php 
require_once "../../modelos/referenciales/MarcaTarjeta.php";

$marcaTarjeta = new MarcaTarjeta();

$idmarcatarjeta = isset($_POST["idmarcatarjeta"])? limpiarCadena($_POST["idmarcatarjeta"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idmarcatarjeta)){
            $rspta = $marcaTarjeta->insertar($descripcion);
            echo $rspta ? "Marca de Tarjeta registrada" : "Marca de Tarjeta no se pudo registrar";
        }else{
            $rspta = $marcaTarjeta->editar($idmarcatarjeta, $descripcion);
            echo $rspta ? "Marca de Tarjeta actualizada" : "Marca de Tarjeta no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta = $marcaTarjeta->eliminar($idmarcatarjeta);
            echo $rspta ? "Marca de Tarjeta eliminada" : "Marca de Tarjeta no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta=$marcaTarjeta->mostrar($idmarcatarjeta);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$marcaTarjeta->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idmarcatarjeta,
                "1"=>$reg->descripcion,
                "2"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->idmarcatarjeta. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idmarcatarjeta. ')"><i class="fa fa-trash"></i></button>'
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