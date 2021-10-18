<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/compras/NotaCredito.php";

$notacredito = new NotaCredito();

$idcredito = isset($_POST["idcredito"])? limpiarCadena($_POST["idcredito"]): "";
$idcompra = isset($_POST["idcompra"])? limpiarCadena($_POST["idcompra"]): "";
$nrofactcredito = isset($_POST["nroFactCredito"])? limpiarCadena($_POST["nroFactCredito"]): "";
$timbrado = isset($_POST["timbrado"])? limpiarCadena($_POST["timbrado"]): "";
$idproveedor = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]): "";
$idpersonal = $_SESSION["idpersonal"];
$idsucursal = $_SESSION["idsucursal"];
$idtipodoc = isset($_POST["idtipodoc"])? limpiarCadena($_POST["idtipodoc"]): "";
$iddeposito = isset($_POST["iddeposito"])? limpiarCadena($_POST["iddeposito"]): "";
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$obs = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";
$total_compra = isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idcredito)){
            
            $rspta = $notacredito->insertar($idcompra, $nrofactcredito, $timbrado, $idproveedor, $idpersonal, $idsucursal, $idtipodoc, 
            $iddeposito, $fecha_hora, $obs, $total_compra, $_POST["idmercaderia"], $_POST["cantidad"], $_POST["preciocompra"]);

            echo $rspta ? "Nota de Credito registrado" : "No se pudieron registrar todos los datos de la Nota de Credito";

        }else{
            
        }

    break;

    case 'anular':
        $rspta = $notacredito->anular($idcredito, $idcompra);
            echo $rspta ? "Nota de Credito anulado" : "La Nota de Credito no se puede anular";
    break;

    case 'mostrar':
        $rspta=$notacredito->mostrar($idcredito);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalle':
        $idcredito = $_GET['id'];
        $rspta = $notacredito->listarDetalle($idcredito);

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
                <th><span class="resul">'.$iva5.'<br>'.$iva10.'<br></span><h4 id="total2">Gs/.'.$total.'</h4><input type="hidden" name="total_compra" id="total_compra"></th>
            </tfoot>';

    break;

    case 'listar':
        
        $rspta=$notacredito->listar($idsucursal);
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idnotacredidebi,
                "1"=>$reg->nro_nota_credito_debito,
                "2"=>$reg->fecha,
                "3"=>$reg->idcompra,
                "4"=>$reg->proveedor,
                "5"=>$reg->personal,
                "6"=>$reg->monto,
                "7"=>($reg->estado)?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>',
                "8"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idnotacredidebi. ')"><i class="fa fa-eye"></i></button>'. ' <button class="btn btn-danger" onclick="anular('.$reg->idnotacredidebi.', '.$reg->idcompra.')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idnotacredidebi. ')"><i class="fa fa-eye"></i></button>'
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

    case 'listarCompras':

        require_once "../../modelos/compras/FacturaCompra.php";

        $compra = new Compra();

        $rspta=$compra->listarActivos();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="agregarCompra('.$reg->idcompra.')"><span class="fa fa-plus"></span></button>',
                "1"=>$reg->idcompra,
                "2"=>$reg->nrofactura,
                "3"=>$reg->fecha,
                "4"=>$reg->proveedor,
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

    case 'listarCabeceraCompra':

        require_once "../../modelos/compras/FacturaCompra.php";

        $compra = new Compra();
        $rspta = $compra->mostrar($idcompra);
        //codificar el resultado utilizando json
        echo json_encode($rspta);

    break;

    case 'listarDetalleCompra':
        
        $idcompra = $_GET['id'];
        require_once "../../modelos/compras/FacturaCompra.php";

        $compra = new Compra();
        $rspta = $compra->listarDetalle($idcompra);
        $data = Array();
        while($reg = $rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idcompra,
                "1"=>$reg->idmercaderia,
                "2"=>$reg->descripcion,
                "3"=>$reg->cantidad,
                "4"=>$reg->precio,
                "5"=>$reg->iva
            );
        }
        echo json_encode($data);
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

}

?>