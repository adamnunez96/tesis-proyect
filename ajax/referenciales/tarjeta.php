<?php 
require_once "../../modelos/referenciales/Tarjeta.php";

$tarjeta = new Tarjeta();

$idtarjeta = isset($_POST["idtarjeta"])? limpiarCadena($_POST["idtarjeta"]): "";
$idmarca = isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]): "";
$idEntidadEmisora = isset($_POST["identidademisora"])? limpiarCadena($_POST["identidademisora"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idtarjeta)){
            $existencia = $tarjeta->validarExistencia($idmarca, $idEntidadEmisora, $descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $tarjeta->insertar($idmarca, $idEntidadEmisora, $descripcion);
                echo $rspta ? "Tarjeta registrada" : "Tarjeta no se pudo registrar";
            }
        }else{
            $existencia = $tarjeta->validarExistencia($idmarca, $idEntidadEmisora, $descripcion);
            if($existencia > 0){
                echo "Ya existe un registro con esta descripcion. Favor verificar";
            }else{
                $rspta = $tarjeta->editar($idtarjeta, $idmarca, $idEntidadEmisora, $descripcion);
                echo $rspta ? "Tarjeta actualizada" : "Tarjeta no se pudo actualizar";
            }
        }
    break;

    case 'eliminar':
        $rspta = $tarjeta->eliminar($idtarjeta);
            echo $rspta ? "Tarjeta eliminada" : "Tarjeta no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta=$tarjeta->mostrar($idtarjeta);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$tarjeta->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idtarjeta,
                "1"=>$reg->descripcion,
                "2"=>$reg->marca,
                "3"=>$reg->entidad,
                "4"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->idtarjeta. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idtarjeta. ')"><i class="fa fa-trash"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case "selectMarcaTarjeta": //Este debe ser un select que debe de mostrar las ciudades

        require_once "../../modelos/referenciales/MarcaTarjeta.php";

        $marcaTarjeta = new MarcaTarjeta();
        $rspta = $marcaTarjeta->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idmarcatarjeta. '>' . $reg->descripcion . '</option>';
        }

    break;

    case "selectEntidadEmisora": //Este debe ser un select que debe de mostrar las ciudades

        require_once "../../modelos/referenciales/EntidadEmisora.php";

        $entidadEmisora = new EntidadEmisora();
        $rspta = $entidadEmisora->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->identidademisora. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>