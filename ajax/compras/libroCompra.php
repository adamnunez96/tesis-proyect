<?php 

if(strlen(session_id())<1)
session_start();

require_once "../../modelos/compras/LibroCompra.php";

$libroCompra = new LibroCompra();

$fecha_inicio = $_REQUEST['fecha_inicio'];
$fecha_fin = $_REQUEST['fecha_fin'];

switch ($_GET["op"]){

    case 'listar':
        
        $rspta=$libroCompra->listar($fecha_inicio, $fecha_fin);
        //vamos a declarar una array
        $data = Array();

        while($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->idlibrocompra,
                "1"=>$reg->nrofactura,
                "2"=>$reg->fecha,
                "3"=>$reg->proveedor,
                "4"=>$reg->montopagado,
                "5"=>$reg->montoiva5,
                "6"=>$reg->grabiva5,
                "7"=>$reg->montoiva10,
                "8"=>$reg->grabiva10,
                "9"=>$reg->montoexenta,
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