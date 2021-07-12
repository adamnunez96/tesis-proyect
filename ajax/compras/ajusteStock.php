<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/compras/AjusteStock.php";

$ajuste = new AjusteStock();

$idajuste = isset($_POST["idajuste"])? limpiarCadena($_POST["idajuste"]): "";
$idsucursal = $_SESSION["idsucursal"];
$idpersonal = $_SESSION["idpersonal"];
$iddeposito = isset($_POST["iddeposito"])? limpiarCadena($_POST["iddeposito"]): "";
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]): "";
$obs = isset($_POST["obs"])? limpiarCadena($_POST["obs"]): "";
$tipoajuste = isset($_POST["tipo_ajuste"])? limpiarCadena($_POST["tipo_ajuste"]): "";
//$total_compra = isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]): "";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idajuste)){
            $rspta = $ajuste->insertar($idsucursal, $idpersonal, $iddeposito, $fecha_hora, $obs, $tipoajuste, $_POST["idmercaderia"], 
            $_POST["cantidad"], $_POST["preciocompra"]);
            echo $rspta ? "Ajuste de Stock registrado y procesado" : "No se pudieron registrar todos los datos del Ajuste de Stock";
        }else{
            $rspta = $ajuste->editar($idajuste, $idpersonal, $iddeposito, $obs, $tipoajuste, $_POST["idmercaderia"], $_POST["cantidad"], $_POST["preciocompra"]);
            echo $rspta ? "Ajuste de Stock modificado" : "No se pudieron modificar todos los datos del Ajuste de Stock";
        }
    break;

    case 'anular':
        $rspta = $ajuste->anular($idajuste, $tipoajuste);
        echo $rspta ? "Ajuste de Stock anulado" : "Ajuste de Stock no se puede anular";
    break;

    case 'mostrar':
        $rspta=$ajuste->mostrar($idajuste);
        //codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listarDetalle':
        $idajuste = $_GET['id'];

        $rspta = $ajuste->listarDetalle($idajuste);

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
        
        $rspta=$ajuste->listar();
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idajuste,
                "1"=>$reg->fecha,
                "2"=>($reg->tipoajuste)?'<span class="label bg-green">ENTRADA</span>':
                '<span class="label bg-red">SALIDA</span>',
                "3"=>$reg->personal,
                "4"=>$reg->sucursal,
                "5"=>($reg->estado)?'<span class="label bg-green">Procesado</span>':
                '<span class="label bg-red">Anulado</span>',
                "6"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idajuste. ')"><i class="fa fa-eye"></i></button>'. 
                ' <button class="btn btn-primary" onclick="modificar(' .$reg->idajuste. ')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="anular(' .$reg->idajuste. ', '.$reg->tipoajuste.')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idajuste. ')"><i class="fa fa-eye"></i></button>'
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

    case "selectDeposito": //Este debe ser un select que debe de mostrar el deposito de acuerdo a la sucursal en la que estamos

        require_once "../../modelos/referenciales/Deposito.php";

        $deposito = new Deposito();
        $rspta = $deposito->listarDeposito($idsucursal);

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->iddeposito. '>' . $reg->descripcion . '</option>';
        }

    break;

    case 'mostrarDetalle':
        
        $idajuste = $_GET['id'];
        require_once "../../modelos/compras/AjusteStock.php";

        $rspta = $ajuste->listarDetalle($idajuste);
        $data = Array();
        while($reg = $rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idajuste,
                "1"=>$reg->idmercaderia,
                "2"=>$reg->descripcion,
                "3"=>$reg->cantidad,
                "4"=>$reg->precio
            );
        }
        echo json_encode($data);
    break;

}

?>