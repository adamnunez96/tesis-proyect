<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/servicios/Diagnostico.php";

$diagnostico = new Diagnostico();

$iddiagnostico = isset($_POST["iddiagnostico"])? limpiarCadena($_POST["iddiagnostico"]): "";
$idrecepcion = isset($_POST["idrecepcion"])? limpiarCadena($_POST["idrecepcion"]): "";
$idcliente = isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]): "";
$idpersonal = $_SESSION["idpersonal"];
$idsucursal = $_SESSION["idsucursal"];
//$idvehiculo = isset($_POST["idvehiculo"])? limpiarCadena($_POST["idvehiculo"]): "";
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$obs = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";
//$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($iddiagnostico)){
            $rspta = $diagnostico->insertar($idrecepcion, $idcliente, $idpersonal, $idsucursal, $_POST["idvehiculo"], $fecha_hora, $obs);
            echo $rspta ? "Diagnostico registrado" : "No se pudieron registrar todos los datos del Diagnostico";
        }else{

        }

    break;

    case 'anular':
        $rspta = $diagnostico->anular($iddiagnostico);
            echo $rspta ? "Diagnostico anulado" : "Diagnostico no se pudo anular";
    break;

    case 'mostrar':
        $rspta=$diagnostico->mostrar($iddiagnostico);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalle':
        $iddiagnostico = $_GET['id'];

        $rspta = $diagnostico->listarDetalle($iddiagnostico);

        echo '<thead style="background-color:#A9D0F5">
                <th style="width:30px">Opciones</th>
                <th>Id</th>
                <th>Vehiculo</th>
                <th>Chapa</th>
              </thead>';

        while($reg = $rspta->fetch_object()){
            echo '<tr class="filas"><td></td><td>'.$reg->idvehiculo.'</td><td>'.$reg->vehiculo.'</td><td>'.$reg->chapa.'</td></tr>';
        }

    break;

    case 'listar':
        
        $rspta=$diagnostico->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->iddiagnostico,
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->personal,
                "4"=>($reg->estado)?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "5"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->iddiagnostico. ')"><i class="fa fa-eye"></i></button>'. 
                ' <button class="btn btn-danger" onclick="anular(' .$reg->iddiagnostico. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->iddiagnostico. ')"><i class="fa fa-eye"></i></button>'
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

    case 'listarRecepciones':

        require_once "../../modelos/servicios/Recepcion.php";

        $recepcion = new Recepcion();

        $rspta=$recepcion->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarRecepcion('.$reg->idrecepcion.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idrecepcion,
                "2"=>$reg->fecha,
                "3"=>$reg->cliente,
                //"4"=>$reg->vehiculo
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
    break;

    case 'listarCabeceraRecepcion':

        require_once "../../modelos/servicios/Recepcion.php";

        $recepcion = new Recepcion();
        $rspta = $recepcion->mostrar($idrecepcion);
        //codificar el resultado utilizando json
        echo json_encode($rspta);

    break;

    case 'listarDetalleRecepcion':
        
        $idrecepcion = $_GET['id'];
        require_once "../../modelos/servicios/Recepcion.php";

        $recepcion = new Recepcion();
        $rspta = $recepcion->listarDetalle($idrecepcion);
        $data = Array();
        while($reg = $rspta->fetch_object()){
            $data[]=array(
                "idrecepcion"=>$reg->idrecepcion,
                "idvehiculo"=>$reg->idvehiculo,
                "vehiculo"=>$reg->vehiculo,
                "chapa"=>$reg->chapa
            );
        }
        echo json_encode($data);
    
    break;


}

?>