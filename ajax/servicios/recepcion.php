<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/servicios/Recepcion.php";

$recepcion = new Recepcion();

$idrecepcion = isset($_POST["idrecepcion"])? limpiarCadena($_POST["idrecepcion"]): "";
$idcliente = isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]): "";
$idvehiculo = isset($_POST["idvehiculo"])? limpiarCadena($_POST["idvehiculo"]): "";
$idpersonal = $_SESSION["idpersonal"];
$idsucursal = $_SESSION["idsucursal"];
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$motivo = isset($_POST["motivo"])? limpiarCadena($_POST["motivo"]): "";
$obs = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idrecepcion)){
            $rspta = $recepcion->insertar($idcliente, $idvehiculo, $idpersonal, $idsucursal, $fecha_hora, $motivo, $obs);
            echo $rspta ? "Recepcion registrado" : "No se pudieron registrar todos los datos de la Recepcion";
        }else{
            $rspta = $recepcion->modificar($idrecepcion, $idcliente, $idvehiculo, $idpersonal, $idsucursal, $motivo, $obs);
            echo $rspta ? "Recepcion modificado" : "No se pudieron modificar todos los datos de la Recepcion";
        }

    break;

    case 'anular':
        $rspta = $recepcion->anular($idrecepcion);
            echo $rspta ? "Recepcion anulada" : "Recepcion no se puede anular";
    break;

    case 'mostrar':
        $rspta=$recepcion->mostrar($idrecepcion);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$recepcion->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idrecepcion,
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->vehiculo,
                "4"=>($reg->estado)?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "5"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idrecepcion. ')"><i class="fa fa-eye"></i></button>'. 
                ' <button class="btn btn-primary" id="btnModificar" onclick="modificar(' .$reg->idrecepcion. ')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="anular(' .$reg->idrecepcion. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idrecepcion. ')"><i class="fa fa-eye"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case 'listarVehiculos':

        require_once "../../modelos/referenciales/Vehiculo.php";

        $vehiculo = new Vehiculo();

        $rspta=$vehiculo->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarVehiculo('.$reg->idvehiculo.', \''.$reg->vehiculo.'\', \''.$reg->chapa.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idvehiculo,
                "2"=>$reg->vehiculo,
                "3"=>$reg->chapa
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
    break;

    

    case "selectCliente": //Este debe ser un select que debe de mostrar las marcas

        require_once "../../modelos/referenciales/Cliente.php";

        $cliente = new Cliente();
        $rspta = $cliente->listarActivos();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idcliente. '>' . $reg->cliente . '</option>';
        }

    break;

}

?>