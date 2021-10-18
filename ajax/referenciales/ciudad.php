<?php 
require_once "../../modelos/referenciales/Ciudad.php";

$ciudad = new Ciudad();

$idciudad = isset($_POST["idciudad"])? limpiarCadena($_POST["idciudad"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idciudad)){
            $existencia = $ciudad->validarExistencia($descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $ciudad->insertar($descripcion);
                echo $rspta ? "Ciudad registrada" : "Ciudad no se pudo registrar";
            }      
        }else{
            $existencia = $ciudad->validarExistencia($descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $ciudad->editar($idciudad, $descripcion);
                echo $rspta ? "Ciudad actualizada" : "Ciudad no se pudo actualizar";
            }
        }
    break;

    case 'eliminar':
        $rspta = $ciudad->eliminar($idciudad);
            echo $rspta ? "Ciudad desactivada" : "Ciudad no se pudo desactivar";
    break;

    case 'activar':
        $rspta = $ciudad->activar($idciudad);
        echo $rspta ? "Ciudad activada" : "Ciudad no se pudo Activar";
    break;

    case 'mostrar':
        $rspta=$ciudad->mostrar($idciudad);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$ciudad->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idciudad,
                "1"=>$reg->descripcion,
                "2"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "3"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idciudad. ')"><i class="fa fa-pencil"></i></button>'. 
                ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idciudad. ')"><i class="fa fa-close"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idciudad. ')"><i class="fa fa-pencil"></i></button>'. 
                ' <button class="btn btn-primary" onclick="activar(' .$reg->idciudad. ')"><i class="fa fa-check"></i></button>'
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