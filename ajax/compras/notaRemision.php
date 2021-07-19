<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/compras/NotaRemision.php";

$nota = new NotaRemision();

$idnota = isset($_POST["idnota"])? limpiarCadena($_POST["idnota"]): "";
$idsucursal = $_SESSION["idsucursal"];
$iddeposito = isset($_POST["iddeposito"])? limpiarCadena($_POST["iddeposito"]): "";
$idperEnvia = isset($_POST["idperEnvia"])? limpiarCadena($_POST["idperEnvia"]): "";
$idperRecibe = $_SESSION["idpersonal"];
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idnota)){
            $rspta = $nota->insertar($idsucursal, $iddeposito, $idperEnvia, $idperRecibe, $fecha_hora, 
            $_POST["idmercaderia"], $_POST["cantidad"],  $_POST["precio"]);
            echo $rspta ? "Nota de Remision registrado" : "No se pudieron registrar todos los datos de la Nota de Remision";
            
        }else{
            
        }

    break;

    case 'anular':
        $rspta = $nota->anular($idnota);
            echo $rspta ? "Nota de Remision anulado" : "Nota de Remision no se puede anular";
    break;

    case 'mostrar':
        $rspta=$nota->mostrar($idnota);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalle':
        $idnota = $_GET['id'];

        $rspta = $nota->listarDetalle($idnota);

        $total = 0;

        echo '<thead style="background-color:#A9D0F5">
                <th>Opciones</th>
                <th>Id</th>
                <th>Mercaderia</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </thead>';

        while($reg = $rspta->fetch_object()){
            echo '<tr class="filas"><td></td><td>'.$reg->idmercaderia.'</td><td>'.$reg->descripcion.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio.'</td><td>'.$reg->cantidad*$reg->precio.'</td></tr>';
            $total=$total+($reg->precio*$reg->cantidad);
        }

        echo '<tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>TOTAL</th>
                <th><h4 id="total">S/.'.$total.'</h4><input type="hidden" name="total_compra" id="total_compra"></th>
            </tfoot>';

    break;

    case 'listar':
        
        $rspta=$nota->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idnotaremision,
                "1"=>$reg->fecha,
                "2"=>$reg->perrecibe,
                "3"=>$reg->sucursal,
                "4"=>$reg->deposito,
                "5"=>($reg->estado)?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "6"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idnotaremision. ')"><i class="fa fa-eye"></i></button>'. ' <button class="btn btn-danger" onclick="anular(' .$reg->idnotaremision. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idnotaremision. ')"><i class="fa fa-eye"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case 'listarMercaderias':

        require_once "../../modelos/referenciales/Mercaderia.php";

        $mercaderia = new Mercaderia();

        $rspta=$mercaderia->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idmercaderia.',\''.$reg->descripcion.'\','.$reg->preciocompra.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idmercaderia,
                "2"=>$reg->descripcion,
                "3"=>$reg->marca,
                "4"=>$reg->preciocompra,
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


    case "selectPersonalEnvia": //Este debe ser un select que debe de mostrar las marcas

        require_once "../../modelos/referenciales/Personal.php";

        $personal = new Personal();
        $rspta = $personal->listarActivos();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idpersonal. '>' . $reg->personal . '</option>';
        }

    break;

    case "selectDeposito": //Este debe ser un select que debe de mostrar el deposito de acuerdo a la sucursal en la que estamos

        require_once "../../modelos/referenciales/Deposito.php";

        $deposito = new Deposito();
        $rspta = $deposito->listarDeposito($idsucursal);

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->iddeposito. '>' . $reg->descripcion . '</option>';
        }

    break;
}

?>