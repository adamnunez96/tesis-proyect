<?php 
require_once "../../modelos/referenciales/Mercaderia.php";

$mercaderia = new Mercaderia;

$idmercaderia = isset($_POST["idmercaderia"])? limpiarCadena($_POST["idmercaderia"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]): "";
$idmarca = isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]): "";
$idtipoimpuesto = isset($_POST["idtipoimpuesto"])? limpiarCadena($_POST["idtipoimpuesto"]): "";
$precioCompra = isset($_POST["precioCompra"])? limpiarCadena($_POST["precioCompra"]): "";
$precioVenta = isset($_POST["precioVenta"])? limpiarCadena($_POST["precioVenta"]): "";
$imagen = isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]): "";


switch ($_GET["op"]){
    case 'guardaryeditar':

        if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])){
            $imagen = $_POST["imagenactual"];
        }else{
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png"){

                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../../files/mercaderias/" . $imagen);
            }
        }

        if(empty($idmercaderia)){
            $rspta = $mercaderia->insertar($idtipoimpuesto, $idmarca, $descripcion, $precioCompra, $precioVenta, $imagen);
            echo $rspta ? "Mercaderia registrada" : "Mercaderia no se pudo registrar";
        }else{
            $rspta = $mercaderia->editar($idmercaderia, $idtipoimpuesto, $idmarca, $descripcion, $precioCompra, $precioVenta, $imagen);
            echo $rspta ? "Mercaderia actualizado" : "Mercaderia no se pudo actualizar";
        }
    break;

    case 'desactivar':
        $rspta = $mercaderia->desactivar($idmercaderia);
            echo $rspta ? "Mercaderia desactivado" : "Proveedor no se pudo desactivar";
    break;

    case 'activar':
        $rspta = $mercaderia->activar($idmercaderia);
            echo $rspta ? "Mercaderia activado" : "Proveedor no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$mercaderia->mostrar($idmercaderia);
        //decodificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        
        $rspta=$mercaderia->listar();
        //vamos a declarar una array
        $data = Array();
            
        while($reg=$rspta->fetch_object()){
            $data[]=array(

                "0"=>$reg->idmercaderia,
                "1"=>$reg->descripcion,
                "2"=>$reg->marca,
                "3"=>$reg->preciocompra,
                "4"=>$reg->precioventa,
                "5"=>$reg->impuesto,
                "6"=>"<img src='../../files/mercaderias/".$reg->imagen."' height='50px' width='50px'>",
                "7"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
                '<span class="label bg-red">Desactivado</span>',
                "8"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar(' .$reg->idmercaderia. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-danger" onclick="desactivar(' .$reg->idmercaderia. ')"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning" onclick="mostrar(' .$reg->idmercaderia. ')"><i class="fa fa-pencil"></i></button>'. ' <button class="btn btn-primary" onclick="activar(' .$reg->idmercaderia. ')"><i class="fa fa-check"></i></button>'
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results);
        
    break;

    case "selectMarca": //Este debe ser un select que debe de mostrar las marcas

        require_once "../../modelos/referenciales/Marca.php";

        $marca = new Marca();
        $rspta = $marca->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idmarca. '>' . $reg->descripcion . '</option>';
        }

    break;

    case "selectTipoImpuesto": //Este debe ser un select que debe de mostrar los tipos de impuestos

        require_once "../../modelos/referenciales/TipoImpuesto.php";

        $tipoImpuesto = new TipoImpuesto();
        $rspta = $tipoImpuesto->listar();

        while($reg = $rspta->fetch_object()){
            echo '<option value =' . $reg->idtipoimpuesto. '>' . $reg->descripcion . '</option>';
        }

    break;

}

?>