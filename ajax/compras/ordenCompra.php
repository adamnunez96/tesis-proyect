<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/compras/OrdenCompra.php";

$ordenCompra = new OrdenCompra();

$idorden = isset($_POST["idorden"])? limpiarCadena($_POST["idorden"]): "";
$idpedido = isset($_POST["idpedido"])? limpiarCadena($_POST["idpedido"]): "";
$idsucursal = $_SESSION["idsucursal"];
$idpersonal = $_SESSION["idpersonal"];
$idproveedor = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]): "";
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$observacion = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";
$total_compra = isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idorden)){
            $rspta = $ordenCompra->insertar($idpedido, $idsucursal, $idpersonal, $idproveedor, $fecha_hora, $observacion,
             $total_compra, $_POST["idmercaderia"], $_POST["cantidad"], $_POST["preciocompra"]);
            echo $rspta ? "Orden de Compra registrado" : "No se pudieron registrar todos los datos de la Orden de Compra";
            
        }else{
            
        }

    break;

    case 'anular':
        $rspta = $ordenCompra->anular($idorden);
            echo $rspta ? "Orden de Compra anulado" : "Orden de Compra no se puede anular";
    break;

    case 'mostrar':
        $rspta=$ordenCompra->mostrar($idorden);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalle':
        $idorden = $_GET['id'];

        $rspta = $ordenCompra->listarDetalle($idorden);

        $total = 0;

        echo '<thead style="background-color:#A9D0F5">
                <th>Opciones</th>
                <th>Id</th>
                <th>Mercaderia</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
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
        
        $rspta=$ordenCompra->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idordencompra,
                "1"=>$reg->fecha,
                "2"=>$reg->proveedor,
                "3"=>$reg->personal,
                "4"=>$reg->monto,
                "5"=>($reg->estado)?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "6"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idordencompra. ')"><i class="fa fa-eye"></i></button>'. ' <button class="btn btn-danger" onclick="anular(' .$reg->idordencompra. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idordencompra. ')"><i class="fa fa-eye"></i></button>'
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

    case 'listarPedidos':

        require_once "../../modelos/compras/PedidoCompra.php";

        $pedido = new PedidoCompra();

        $rspta=$pedido->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarPedido('.$reg->idpedido.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idpedido,
                "2"=>$reg->fecha,
                "3"=>$reg->personal,
                "4"=>$reg->sucursal
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
    break;

    case "selectProveedor": //Este debe ser un select que debe de mostrar las marcas

        require_once "../../modelos/referenciales/Proveedor.php";

        $proveedor = new Proveedor();
        $rspta = $proveedor->listarActivos();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idproveedor. '>' . $reg->razonsocial . '</option>';
        }

    break;

    case 'listarCabeceraPedido':

        require_once "../../modelos/compras/PedidoCompra.php";

        $pedido = new PedidoCompra();
        $rspta = $pedido->mostrar($idpedido);
        //codificar el resultado utilizando json
        echo json_encode($rspta);

    break;

    case 'listarDetallePedido':
        
        $idpedido = $_GET['id'];
        require_once "../../modelos/compras/PedidoCompra.php";

        $pedido = new PedidoCompra();
        $rspta = $pedido->listarDetalle($idpedido);

        $cont = 0;
        $total = 0;
        $detalle = 0;
        echo '<thead style="background-color:#A9D0F5">
                <th>Opciones</th>
                <th>Id</th>
                <th>Mercaderia</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
                <th>Subtotal</th>
            </thead>';

        while($reg = $rspta->fetch_object()){
            echo '<tr class="filas" id="fila'.$cont.'>
                    <td><button type="button" class="btn btn-info"></button></td>
                    <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('.$cont.')">X</button></td>
                    <td><input type="hidden" name="idmercaderia[]" value="'.$reg->idmercaderia.'">'.$reg->idmercaderia.'</td>
                    <td><input type="hidden" name="descripcion[]" value="'.$reg->descripcion.'">'.$reg->descripcion.'</td>
                    <td><input type="number" name="cantidad[]" value="'.$reg->cantidad.'"></td>
                    <td><input type="hidden" name="preciocompra[]" value="'.$reg->preciocompra.'">'.$reg->preciocompra.'</td>
                    <td><span name="subtotal" id="subtotal'.$reg->cantidad*$reg->preciocompra.'">'.$reg->cantidad*$reg->preciocompra.'</span></td>
                    <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>
                </tr>';
                    $total=$total+($reg->preciocompra*$reg->cantidad);
                    $cont++;
                    $detalle = $detalle+1;
                    $_SESSION['detalle'] = $detalle;
        }

        echo '<tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>TOTAL</th>
                <th><h4 id="total">Gs/.'.$total.'</h4><input type="hidden" name="total_compra" id="total_compra" value="'.$total.'"></th>
            </tfoot>
            <div id="detalle" style="display:none" data-value="'.$_SESSION['detalle'].'"></div>';
    break;


}

?>