<?php 
require_once "../../modelos/referenciales/Sucursal.php";

$sucursal = new Sucursal();

$idsucursal = isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]): "";
$idciudad = isset($_POST["idciudad"])? limpiarCadena($_POST["idciudad"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]): "";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idsucursal)){
            $rspta = $sucursal->insertar($idciudad, $descripcion, $direccion, $telefono);
            echo $rspta ? "Sucursal registrada" : "Sucursal no se pudo registrar";
        }else{
            $rspta = $sucursal->editar($idsucursal, $idciudad, $descripcion, $direccion, $telefono);
            echo $rspta ? "Sucursal actualizada" : "Sucursal no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta = $sucursal->eliminar($idsucursal);
            echo $rspta ? "Sucursal eliminada" : "Sucursal no se pudo eliminar";
    break;

    case 'mostrar':
        $rspta=$sucursal->mostrar($idsucursal);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$sucursal->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idsucursal,
                "1"=>$reg->descripcion,
                "2"=>$reg->ciudad,
                "3"=>$reg->direccion,
                "4"=>$reg->telefono,
                "5"=>'<button class="btn btn-warning" onclick="mostrar(' .$reg->idsucursal. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="eliminar(' .$reg->idsucursal. ')"><i class="fa fa-trash"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case "selectCiudad": //Este debe ser un select que debe de mostrar las ciudades

        require_once "../../modelos/referenciales/Ciudad.php";

        $ciudad = new Ciudad();
        $rspta = $ciudad->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idciudad. '>' . $reg->descripcion . '</option>';
        }

    break;

    case "selectSucursal": //Esto es un select que muestra las sucursales en el login

        $rspta = $sucursal->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idsucursal. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>