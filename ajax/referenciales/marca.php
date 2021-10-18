<?php 
require_once "../../modelos/referenciales/Marca.php";

$marca = new Marca();

$idmarca = isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idmarca)){
            $existencia = $marca->validarExistencia($descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $marca->insertar($descripcion);
                echo $rspta ? "Marca registrada" : "Marca no se pudo registrar";
            }
        }else{
            $existencia = $marca->validarExistencia($descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $marca->editar($idmarca, $descripcion);
                echo $rspta ? "Marca actualizada" : "Marca no se pudo actualizar";
            }
        }
    break;

    case 'eliminar':
        $rspta = $marca->eliminar($idmarca);
            echo $rspta ? "Marca eliminada" : "Marca no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta=$marca->mostrar($idmarca);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$marca->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idmarca,
                "1"=>$reg->descripcion,
                "2"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->idmarca. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idmarca. ')"><i class="fa fa-trash"></i></button>'
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