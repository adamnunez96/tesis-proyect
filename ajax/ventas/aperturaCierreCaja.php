<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/ventas/AperturaCierreCaja.php";

$aperturaCierre = new AperturaCierreCaja();

$idapertura = isset($_POST["idapertura"])? limpiarCadena($_POST["idapertura"]): "";
$idpersonal = $_SESSION["idpersonal"];
$idsucursal = $_SESSION["idsucursal"];
$idcaja = isset($_POST["idcaja"])? limpiarCadena($_POST["idcaja"]): "";
$fecha_apertura = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$monto_apertura = isset($_POST["montoApertura"])? limpiarCadena($_POST["montoApertura"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idapertura)){
            $existencia = $aperturaCierre->validarCaja($fecha_apertura, $idsucursal, $idcaja);
            print_r($existencia);
            if($existencia > 0){
                echo "La Caja ya esta Aperturada";
            }else{
                $rspta = $aperturaCierre->insertar( $idpersonal, $idsucursal, $idcaja ,$fecha_apertura, $monto_apertura);
                echo $rspta ? "Caja Aperturada" : "No se pudieron registrar todos los datos de la Apertura";
            }
        }else{
            
        }

    break;

    case 'cierre':
        $rspta = $aperturaCierre->cierre($idapertura);
            echo $rspta ? "Caja Cerrada" : "Caja no se pudo cerrar";
    break;

    /*case 'mostrar':
        $rspta=$pedidoCompra->mostrar($idpedido);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;*/

    /*case 'listarDetalle':
        
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

    break;*/

    case 'listar':
        
        $rspta=$aperturaCierre->listar($idsucursal);
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idaperturacierre,
                "1"=>$reg->caja,
                "2"=>$reg->personal,
                "3"=>$reg->fechaapertura,
                "4"=>$reg->montoapertura,
                "5"=>$reg->fechacierre,
                "6"=>$reg->montocierre,
                "7"=>$reg->sucursal,
                "8"=>($reg->estado)?'<span class="label bg-green">Abierto</span>':
                '<span class="label bg-red">Cerrado</span>',
                "9"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idaperturacierre. ')"><i class="fa fa-eye"></i></button>'. 
                ' <button class="btn btn-primary" onclick="cierre(' .$reg->idaperturacierre. ')"><span class="label">CIERRE</span></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idaperturacierre. ')"><i class="fa fa-eye"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case "selectCaja": //Este debe ser un select que debe de mostrar las marcas

        require_once "../../modelos/referenciales/Caja.php";

        $proveedor = new Caja();
        $rspta = $proveedor->listarActivos();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idcaja. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>