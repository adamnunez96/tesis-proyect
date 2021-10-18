<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/servicios/OrdenTrabajo.php";

$orden = new OrdenTrabajo();

$idorden = isset($_POST["idorden"])? limpiarCadena($_POST["idorden"]): "";
$idpresupuesto = isset($_POST["idpresupuesto"])? limpiarCadena($_POST["idpresupuesto"]): "";
$idcliente = isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]): "";
$idpersonal = $_SESSION["idpersonal"];
$idsucursal = $_SESSION["idsucursal"];
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$obs = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idorden)){
            $rspta = $orden->insertar($idpresupuesto, $idcliente, $idpersonal, $idsucursal, $fecha_hora, $obs, $_POST["idvehiculo"], $_POST["idmercaderia"], $_POST["cantidad"], $_POST["precioventa"]);
            echo $rspta ? "Orden Trabajo registrado" : "No se registraron todos los datos de la Orden de Trabajo";
        }else{

        }

    break;

    case 'anular':
        $rspta = $orden->anular($idorden, $idpresupuestoservicio);
            echo $rspta ? "Orden Trabajo anulado" : "Orden Trabajo no se pudo anular";
    break;

    case 'mostrar':
        $rspta=$orden->mostrar($idorden);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalleVehiculo':
        $idorden = $_GET['id'];

        $rspta = $orden->listarDetalle($idorden);

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
        $idorden = $_GET['id'];

        $rspta = $orden->listarDetalle($idorden);

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
        
        $rspta=$orden->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idordentrabajo,
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->personal,
                "4"=>($reg->estado)==1?'<span class="label bg-green">Aceptado</span>':
                ($reg->estado==0?'<span class="label bg-red">Anulado</span>': "<span class='label bg-yellow'>Utilizado</span>"),
                "5"=>($reg->estado)==1?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idordentrabajo. ')"><i class="fa fa-eye"></i></button>'. 
                ' <button class="btn btn-danger" onclick="anular('.$reg->idordentrabajo.', '.$reg->idpresupuestoservicio.')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idordentrabajo. ')"><i class="fa fa-eye"></i></button>'
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

    case 'listarPresupuestos':

        require_once "../../modelos/servicios/Presupuesto.php";

        $presupuesto = new Presupuesto();

        $rspta=$presupuesto->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarPresupuesto('.$reg->idpresupuestoservicio.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idpresupuestoservicio,
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

    case 'listarCabeceraPresupuesto':

        require_once "../../modelos/servicios/Presupuesto.php";

        $presupuesto = new Presupuesto();
        $rspta = $presupuesto->mostrar($idpresupuesto);
        //codificar el resultado utilizando json
        echo json_encode($rspta);

    break;

    case 'listarDetallePresupuesto':
        
        $idpresupuesto = $_GET['id'];
        require_once "../../modelos/servicios/Presupuesto.php";

        $presupuesto = new Presupuesto();
        $rspta = $presupuesto->listarDetalle($idpresupuesto);
        $data = Array();
        while($reg = $rspta->fetch_object()){
            $data[]=array(
                "idpresupuesto"=>$reg->idpresupuestoservicio,
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

    case 'listarDetallePresupuesto2':
        
        $idpresupuesto = $_GET['id'];
        require_once "../../modelos/servicios/Presupuesto.php";

        $presupuesto = new Presupuesto();
        $rspta = $presupuesto->listarDetalle($idpresupuesto);
        $data = Array();
        while($reg = $rspta->fetch_object()){
            $data[]=array(
                "idpresupuesto"=>$reg->idpresupuestoservicio,
                "idmercaderia"=>$reg->idmercaderia,
                "mercaderia"=>$reg->mercaderia,
                "cantidad"=>$reg->cantidad,
                "precio"=>$reg->precio
            );
        }
        echo json_encode($data);
    
    break;

}

?>