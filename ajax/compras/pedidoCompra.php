<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/compras/pedidoCompra.php";

$pedidoCompra = new PedidoCompra();

$idpedido = isset($_POST["idpedido"])? limpiarCadena($_POST["idpedido"]): "";
$idsucursal = $_SESSION["idsucursal"];
$idpersonal = $_SESSION["idpersonal"];
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$observacion = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idpedido)){
            $rspta = $pedidoCompra->insertar($idsucursal, $idpersonal, $fecha_hora, $observacion, $_POST["idmercaderia"], $_POST["cantidad"]);
            echo $rspta ? "Pedido Compra registrado" : "No se pudieron registrar todos los datos del Pedido Compra";
            
        }else{
            
        }

    break;

    case 'anular':
        $rspta = $pedidoCompra->anular($idpedido);
            echo $rspta ? "Pedido Compra anulado" : "Pedido Compra no se puede anular";
    break;

    case 'mostrar':
        $rspta=$pedidoCompra->mostrar($idpedido);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalle':
        
        $idpedido = $_GET['id'];
        $rspta = $pedidoCompra->listarDetalle($idpedido);

        $total = 0;

        echo '<thead style="background-color:#A9D0F5">
                <th>Opciones</th>
                <th>Id</th>
                <th>Mercaderia</th>
                <th>Cantidad</th>
            </thead>';

        while($reg = $rspta->fetch_object()){
            echo '<tr class="filas"><td></td><td>'.$reg->idmercaderia.'</td><td>'.$reg->descripcion.'</td><td>'.$reg->cantidad.'</td></tr>';
            //$total=$total+($reg->precio_compra*$reg->cantidad);
        }

        echo '<tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>';

    break;

    case 'listar':
        
        $rspta=$pedidoCompra->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idpedido,
                "1"=>$reg->fecha,
                "2"=>$reg->personal,
                "3"=>$reg->sucursal,
                "4"=>($reg->estado)?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "5"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idpedido. ')"><i class="fa fa-eye"></i></button>'. ' <button class="btn btn-danger" onclick="anular(' .$reg->idpedido. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idpedido. ')"><i class="fa fa-eye"></i></button>',
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
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idmercaderia.',\''.$reg->descripcion.'\')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idmercaderia,
                "2"=>$reg->descripcion,
                "3"=>$reg->marca,
                "4"=>"<img src='../../files/mercaderias/".$reg->imagen."' height='50px' width='50px'>",
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