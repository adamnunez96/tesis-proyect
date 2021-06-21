<?php 
require_once "../../modelos/referenciales/Caja.php";

$caja = new Caja();

$idcaja = isset($_POST["idcaja"])? limpiarCadena($_POST["idcaja"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idcaja)){
            $rspta = $caja->insertar($descripcion);
            echo $rspta ? "Caja registrada" : "Caja no se pudo registrar";
        }else{
            $rspta = $caja->editar($idcaja, $descripcion);
            echo $rspta ? "Caja actualizada" : "Caja no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta = $caja->eliminar($idcaja);
            echo $rspta ? "Caja eliminada" : "Caja no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta=$caja->mostrar($idcaja);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$caja->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idcaja,
                "1"=>$reg->descripcion,
                "2"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->idcaja. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idcaja. ')"><i class="fa fa-trash"></i></button>'
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