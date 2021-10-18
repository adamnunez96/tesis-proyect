<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/ventas/FacturaVenta.php";

$venta = new Venta();

$idventa = isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]): "";
$idservicio = isset($_POST["idservicio"])? limpiarCadena($_POST["idservicio"]): "";
$nrofactura = isset($_POST["nrofactura"])? limpiarCadena($_POST["nrofactura"]): "";
$idcliente = isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]): "";
$idpersonal = $_SESSION["idpersonal"];
$idsucursal = $_SESSION["idsucursal"];
$idcondicion = isset($_POST["idformapago"])? limpiarCadena($_POST["idformapago"]): "";
$idtipodoc = isset($_POST["idtipodoc"])? limpiarCadena($_POST["idtipodoc"]): "";
$idtimbrado = isset($_POST["idtimbrado"])? limpiarCadena($_POST["idtimbrado"]): "";

$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
//$obs = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";
$total_venta = isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]): "";
$cuota = isset($_POST["cuota"])? limpiarCadena($_POST["cuota"]): "";

$total_iva5 = isset($_POST["total_iva5"])? limpiarCadena($_POST["total_iva5"]): "";
$total_iva10 = isset($_POST["total_iva10"])? limpiarCadena($_POST["total_iva10"]): "";
$total_exenta = isset($_POST["total_exenta"])? limpiarCadena($_POST["total_exenta"]): "";
$liq_iva5 = isset($_POST["liq_iva5"])? limpiarCadena($_POST["liq_iva5"]): "";
$liq_iva10 = isset($_POST["liq_iva10"])? limpiarCadena($_POST["liq_iva10"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idventa)){
            
            $rspta = $venta->insertar($idservicio, $nrofactura, $idcliente, $idpersonal, $idsucursal, $idtimbrado, $idcondicion, $idtipodoc, 
            $fecha_hora, $total_venta, $_POST["idmercaderia"], $_POST["cantidad"], $_POST["precioventa"], $total_exenta, 
            $total_iva5, $total_iva10, $liq_iva5, $liq_iva10, $cuota);

            echo $rspta ? "Factura de Venta registrado" : "No se pudieron registrar todos los datos de la Factura de Venta";

        }else{
            
        }

    break;

    case 'anular':
        $rspta = $venta->anular($idventa);
            echo $rspta ? "Factura de Venta anulado" : "Factura de Venta no se puede anular";
    break;

    case 'mostrar':
        $rspta=$venta->mostrar($idventa);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalle':
        $idventa = $_GET['id'];
        $rspta = $venta->listarDetalle($idventa);

        $total = 0;

        echo '<thead style="background-color:#A9D0F5">
                <th>Opciones</th>
                <th>Id</th>
                <th>Mercaderia</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Iva</th>
                <th>Subtotal</th>
            </thead>';

        while($reg = $rspta->fetch_object()){
            $subtotal = $reg->cantidad*$reg->precio;
            echo '<tr class="filas"><td></td><td>'.$reg->idmercaderia.'</td>
            <td>'.$reg->descripcion.'</td>
            <td>'.$reg->cantidad.'</td>
            <td>'.$reg->precio.'</td>
            <td>'.$reg->iva.'</td>
            <td>'.$subtotal.'</td>
            </tr>';
            $total=$total+($reg->precio*$reg->cantidad);

            if($reg->iva == 5){
                $iva5 += ($subtotal * 5)/100;
            }else if($reg->iva == 10){
                $iva10 += ($subtotal * 10)/100;
            }else{
                $exenta += $subtotal;
            }
        }
        $total5 = $subtotal - $iva5;
        $total10 = $subtotal - $iva10;
        echo '<tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><span class="resul">IVA 5 Inc.<br>IVA 10 Inc.<br>TOTAL</span></th>
                <th><span class="resul">'.$iva5.'<br>'.$iva10.'<br></span><h4 id="total2">Gs/.'.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
            </tfoot>';

    break;

    case 'listar':
        
        $rspta=$venta->listar($idsucursal);
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idfacturaventa,
                "1"=>$reg->nrofactura,
                "2"=>$reg->fecha,
                "3"=>$reg->cliente,
                "4"=>$reg->personal,
                "5"=>$reg->monto,
                "6"=>($reg->estado)==1?'<span class="label bg-green">Aceptado</span>':
                ($reg->estado==0?'<span class="label bg-red">Anulado</span>':'<span class="label bg-yellow">Utilizado</span>'),
                "7"=>($reg->estado)==1?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idfacturaventa. ')"><i class="fa fa-eye"></i></button>'. 
                ' <button class="btn btn-danger" onclick="anular(' .$reg->idfacturaventa. ')"><i class="fa fa-close"></i></button>' :
                ($reg->estado == 0 ? '<button class="btn btn-warning" onclick="mostrar(' .$reg->idfacturaventa. ')"><i class="fa fa-eye"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idfacturaventa. ')"><i class="fa fa-eye"></i></button>')
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

    case 'listarServicios':

        require_once "../../modelos/servicios/Servicio.php";

        $orden = new Servicio();

        $rspta=$orden->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarServicio('.$reg->idservicio.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idservicio,
                "2"=>$reg->fecha,
                "3"=>$reg->cliente,
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

    case 'listarCabeceraServicio':

        require_once "../../modelos/servicios/Servicio.php";

        $servicio = new Servicio();
        $rspta = $servicio->mostrar($idservicio);
        //codificar el resultado utilizando json
        echo json_encode($rspta);

    break;

    case 'listarDetalleServicio':
        
        $idservicio = $_GET['id'];
        require_once "../../modelos/servicios/Servicio.php";

        $servicio = new Servicio();
        $rspta = $servicio->listarDetalle2($idservicio);
        $data = Array();
        while($reg = $rspta->fetch_object()){
            $data[]=array(
                "idservicio"=>$reg->idservicio,
                "idmercaderia"=>$reg->idmercaderia,
                "descripcion"=>$reg->descripcion,
                "cantidad"=>$reg->cantidad,
                "precio"=>$reg->precio,
                "iva"=>$reg->iva
            );
        }
        echo json_encode($data);
    break;

    case "selectCliente": //Este debe ser un select que debe de mostrar los proveedores

        require_once "../../modelos/referenciales/Cliente.php";

        $cliente = new Cliente();
        $rspta = $cliente->listarActivos();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idcliente. '>' . $reg->cliente . '</option>';
        }

    break;

    /*case "selectDeposito": //Este debe ser un select que debe de mostrar el deposito de acuerdo a la sucursal en la que estamos

        require_once "../../modelos/referenciales/Deposito.php";

        $deposito = new Deposito();
        $rspta = $deposito->listarDeposito($idsucursal);

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->iddeposito. '>' . $reg->descripcion . '</option>';
        }

    break;*/

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

    case 'recuperarTimbrado':

        require_once "../../modelos/ventas/Timbrado.php";

        $timbrado = new Timbrado();
        $rspta = $timbrado->recuperarTimbrado($idtimbrado);
        $data = Array();
        if($reg = $rspta->fetch_object()){
            $data[]=array(
                "timbrado"=>$reg->nrotimbrado,
                "nro1"=>$reg->nro1,
                "nro2"=>$reg->nro2,
                "nrofactura"=>$reg->nrofactura,
            );
        }
        //codificar el resultado utilizando json
        echo json_encode($data);

    break;

    

}

?>