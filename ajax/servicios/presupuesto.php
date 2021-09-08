<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/servicios/Presupuesto.php";

$presupuesto = new Presupuesto();

$idpresupuesto = isset($_POST["idpresupuesto"])? limpiarCadena($_POST["idpresupuesto"]): "";
$iddiagnostico = isset($_POST["iddiagnostico"])? limpiarCadena($_POST["iddiagnostico"]): "";
$idcliente = isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]): "";
$idpersonal = $_SESSION["idpersonal"];
$idsucursal = $_SESSION["idsucursal"];
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$obs = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idpresupuesto)){
            $rspta = $presupuesto->insertar($iddiagnostico, $idcliente, $idpersonal, $idsucursal, $fecha_hora, $obs, $_POST["idvehiculo"], $_POST["idmercaderia"], $_POST["cantidad"], $_POST["precioventa"]);
            echo $rspta ? "Presupuesto registrado" : "No se pudieron registrar todos los datos del Presupuesto";
        }else{

        }

    break;

    case 'anular':
        $rspta = $presupuesto->anular($idpresupuesto);
            echo $rspta ? "Diagnostico anulado" : "Diagnostico no se pudo anular";
    break;

    case 'mostrar':
        $rspta=$presupuesto->mostrar($idpresupuesto);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalleVehiculo':
        $idpresupuesto = $_GET['id'];

        $rspta = $presupuesto->listarDetalle($idpresupuesto);

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

    case 'listarDetalleManoObra':
        $idpresupuesto = $_GET['id'];

        $rspta = $presupuesto->listarDetalle($idpresupuesto);

        $total = 0;

        echo '<thead style="background-color:#A9D0F5">
                 <th style="width:30px">Opciones</th>
                 <th>Id</th>
                 <th>Mercaderia</th>
                 <th>Cantidad</th>
                 <th>Precio</th>
                 <th>Subtotal</th>
             </thead>';

        while($reg = $rspta->fetch_object()){
            echo '<tr class="filas"><td></td><td>'.$reg->idmercaderia.'</td><td>'.$reg->mercaderia.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio.'</td><td>'.$reg->cantidad*$reg->precio.'</td></tr>';
            $total=$total+($reg->precio*$reg->cantidad);
        }

        echo '<tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>TOTAL</th>
                <th><h4 id="total">S/.'.$total.'</th>
            </tfoot>';
    break;

    case 'listar':
        
        $rspta=$presupuesto->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idpresupuestoservicio,
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->personal,
                "4"=>($reg->estado)?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "5"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idpresupuestoservicio. ')"><i class="fa fa-eye"></i></button>'. 
                ' <button class="btn btn-danger" onclick="anular(' .$reg->idpresupuestoservicio. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idpresupuestoservicio. ')"><i class="fa fa-eye"></i></button>'
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

    case 'listarDiagnosticos':

        require_once "../../modelos/servicios/Diagnostico.php";

        $diagnostico = new Diagnostico();

        $rspta=$diagnostico->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDiagnostico('.$reg->iddiagnostico.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->iddiagnostico,
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

    case 'listarCabeceraDiagnostico':

        require_once "../../modelos/servicios/Diagnostico.php";

        $dianostico = new Diagnostico();
        $rspta = $dianostico->mostrar($iddiagnostico);
        //codificar el resultado utilizando json
        echo json_encode($rspta);

    break;

    case 'listarDetalleDiagnostico':
        
        $iddiagnostico = $_GET['id'];
        require_once "../../modelos/servicios/Diagnostico.php";

        $diagnostico = new Diagnostico();
        $rspta = $diagnostico->listarDetalle($iddiagnostico);
        $data = Array();
        while($reg = $rspta->fetch_object()){
            $data[]=array(
                "iddiagnostico"=>$reg->iddiagnostico,
                "idvehiculo"=>$reg->idvehiculo,
                "vehiculo"=>$reg->vehiculo,
                "chapa"=>$reg->chapa
            );
        }
        echo json_encode($data);
    
    break;

    case 'listarMercaderias':

        require_once "../../modelos/referenciales/Mercaderia.php";

        $mercaderia = new Mercaderia();

        $rspta=$mercaderia->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarMercaderia('.$reg->idmercaderia.',\''.$reg->descripcion.'\','.$reg->precioventa.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idmercaderia,
                "2"=>$reg->descripcion,
                "3"=>$reg->marca,
                "4"=>$reg->precioventa,
                "5"=>"<img src='../../files/mercaderias/".$reg->imagen."' height='50px' width='50px'>",
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