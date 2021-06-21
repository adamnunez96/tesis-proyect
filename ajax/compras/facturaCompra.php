<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/compras/FacturaCompra.php";

$compra = new Compra();

$idcompra = isset($_POST["idcompra"])? limpiarCadena($_POST["idcompra"]): "";
$idorden = isset($_POST["idorden"])? limpiarCadena($_POST["idorden"]): "";
$nrofactura = isset($_POST["nrofactura"])? limpiarCadena($_POST["nrofactura"]): "";
$idproveedor = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]): "";
$idpersonal = $_SESSION["idpersonal"];
$idsucursal = $_SESSION["idsucursal"];
$idformapago = isset($_POST["idformapago"])? limpiarCadena($_POST["idformapago"]): "";
$idtipodoc = isset($_POST["idtipodoc"])? limpiarCadena($_POST["idtipodoc"]): "";
$iddeposito = isset($_POST["iddeposito"])? limpiarCadena($_POST["iddeposito"]): "";

$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$obs = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";
$total_compra = isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]): "";
$cuota = isset($_POST["cuota"])? limpiarCadena($_POST["cuota"]): "";

$total_iva5 = isset($_POST["total_iva5"])? limpiarCadena($_POST["total_iva5"]): "";
$total_iva10 = isset($_POST["total_iva10"])? limpiarCadena($_POST["total_iva10"]): "";
$total_exenta = isset($_POST["total_exenta"])? limpiarCadena($_POST["total_exenta"]): "";
$liq_iva5 = isset($_POST["liq_iva5"])? limpiarCadena($_POST["liq_iva5"]): "";
$liq_iva10 = isset($_POST["liq_iva10"])? limpiarCadena($_POST["liq_iva10"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idcompra)){
            
            $rspta = $compra->insertar($idorden, $nrofactura, $idproveedor, $idpersonal, $idsucursal, $idformapago, $idtipodoc, 
            $iddeposito, $fecha_hora, $obs, $total_compra, $_POST["idmercaderia"], $_POST["cantidad"], $_POST["preciocompra"], $total_exenta, 
            $total_iva5, $total_iva10, $liq_iva5, $liq_iva10, $cuota);

            echo $rspta ? "Factura de Compra registrado" : "No se pudieron registrar todos los datos de la Factura de Compra";

        }else{
            
        }

    break;

    case 'anular':
        $rspta = $compra->anular($idcompra);
            echo $rspta ? "Factura de Compra anulado" : "Factura de Compra no se puede anular";
    break;

    case 'mostrar':
        $rspta=$compra->mostrar($idcompra);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalle':
        $idcompra = $_GET['id'];

        $rspta = $compra->listarDetalle($idcompra);

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
        
        $rspta=$compra->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idcompra,
                "1"=>$reg->nrofactura,
                "2"=>$reg->fecha,
                "3"=>$reg->proveedor,
                "4"=>$reg->personal,
                "5"=>$reg->monto,
                "6"=>($reg->estado)?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "7"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idcompra. ')"><i class="fa fa-eye"></i></button>'. ' <button class="btn btn-danger" onclick="anular(' .$reg->idcompra. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idcompra. ')"><i class="fa fa-eye"></i></button>'
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
                "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idmercaderia.',\''.$reg->descripcion.'\','.$reg->preciocompra.','.$reg->tipo.')"><span class="fa fa-plus"></span></button>',
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

    case 'listarOrdenes':

        require_once "../../modelos/compras/OrdenCompra.php";

        $orden = new OrdenCompra();

        $rspta=$orden->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarPedido('.$reg->idordencompra.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idordencompra,
                "2"=>$reg->fecha,
                "3"=>$reg->proveedor,
                "4"=>$reg->personal,
                "5"=>$reg->monto
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
    break;

    case 'listarCabeceraOrdenCompra':

        require_once "../../modelos/compras/OrdenCompra.php";

        $orden = new OrdenCompra();
        $rspta = $orden->mostrar($idorden);
        //codificar el resultado utilizando json
        echo json_encode($rspta);

    break;

    case 'listarDetalleOrdenCompra':
        
        $idorden = $_GET['id'];
        require_once "../../modelos/compras/OrdenCompra.php";

        $orden = new OrdenCompra();
        $rspta = $orden->listarDetalle($idorden);

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

    case "selectProveedor": //Este debe ser un select que debe de mostrar los proveedores

        require_once "../../modelos/referenciales/Proveedor.php";

        $proveedor = new Proveedor();
        $rspta = $proveedor->listarActivos();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idproveedor. '>' . $reg->razonsocial . '</option>';
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

    case "selectTipoDocumento": //Este debe ser un select que debe de mostrar los tipos de documentos

        require_once "../../modelos/referenciales/TipoDocumento.php";

        $documento = new TipoDocumento();
        $rspta = $documento->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idtipodocumento. '>' . $reg->descripcion . '</option>';
        }

    break;

    case "selectFormaPago": //Este debe ser un select que debe de mostrar las marcas

        require_once "../../modelos/referenciales/FormaPago.php";

        $pago = new FormaPago();
        $rspta = $pago->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idformapago. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>