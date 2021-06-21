<?php 
require_once "../../modelos/referenciales/Vehiculo.php";

$vehiculo = new Vehiculo();

$idvehiculo = isset($_POST["idvehiculo"])? limpiarCadena($_POST["idvehiculo"]): "";
$idmarca = isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]): "";
$modelo = isset($_POST["modelo"])? limpiarCadena($_POST["modelo"]): "";
$chapa = isset($_POST["chapa"])? limpiarCadena($_POST["chapa"]): "";
$observacion = isset($_POST["observacion"])? limpiarCadena($_POST["observacion"]): "";
$anho = isset($_POST["anho"])? limpiarCadena($_POST["anho"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idvehiculo)){
            $rspta = $vehiculo->insertar($idmarca, $modelo, $chapa, $observacion, $anho);
            echo $vehiculo ? "Vehiculo registrado" : "Vehiculo no se pudo registrar";
        }else{
            $rspta = $vehiculo->editar($idvehiculo, $idmarca, $modelo, $chapa, $observacion, $anho);
            echo $rspta ? "Vehiculo actualizado" : "Vehiculo no se pudo actualizar";
        }
    break;

    case 'desactivar':
        $rspta = $vehiculo->desactivar($idvehiculo);
            echo $rspta ? "Vehiculo desactivado" : "Vehiculo no se pudo desactivar";
    break;

    case 'activar':
        $rspta = $vehiculo->activar($idvehiculo);
            echo $rspta ? "Vehiculo activado" : "Vehiculo no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$vehiculo->mostrar($idvehiculo);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$vehiculo->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idvehiculo,
                "1"=>$reg->modelo,
                "2"=>$reg->marca,
                "3"=>$reg->chapa,
                "4"=>$reg->observacion,
                "5"=>$reg->anho,
                "6"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "7"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idvehiculo. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="desactivar(' .$reg->idvehiculo. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idvehiculo. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-primary" onclick="activar(' .$reg->idvehiculo. ')"><i class="fa fa-check"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case "selectMarca": //Este debe ser un select que debe de mostrar las ciudades

        require_once "../../modelos/referenciales/Marca.php";

        $marca = new Marca();
        $rspta = $marca->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idmarca. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>