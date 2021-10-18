<?php
    //activamos el almacenamiento en el buffer
    ob_start();

    if(strlen(session_id()) < 1)
    session_start();
  
    if(!isset($_SESSION["usuario"])){
      echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
    }else{
      
      if($_SESSION['VENTAS']==1){

//incluimos el archivo factura.php
require('Factura.php');

//establecemos los datos de la empresa
/*$logo = "logo.jpg";
$ext_logo = "jpg";*/
$empresa = "Nuñez Repuestos";
$documento = "8000000-1";
$direccion = "calle Pai Puku c/ Avda Gaspar Rodriguez de Francia \n Suc. Avda Gaspar Rodriguez de Francia Esq. Guarnicion";
$ciudad = "Nanawa";
$telefono = "021-214236";
$email = "nunezrepuestos@gmail.com";

//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/ventas/FacturaVenta.php";
$venta = new Venta();
$rsptav = $venta->ventaCabecera($_GET["id"]);
// Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();

//Establecemos la configuracion de la factura
$pdf = new PDF_Invoice('P','mm', 'A4');
$pdf->AddPage();

//Enviamos los datos de la empresa al método addSociete de la clase Factura
$pdf->addSociete(utf8_decode($empresa),
                  $documento."\n" .
                  utf8_decode($direccion)."\n".
                  utf8_decode($ciudad)."\n".
                  utf8_decode("Teléfono: ").$telefono."\n" .
                  "correo : ".$email,null,null);
$pdf->fact_dev( "$regv->tipo_documento ", "$regv->nrofactura" );
$pdf->temporaire( "" );
$pdf->addDate( $regv->fecha);

//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
$pdf->addClientAdresse(utf8_decode($regv->cliente),null,$regv->tipo_documento.": ".$regv->num_documento,null,"Telefono: ".null);

//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>23,
             "DESCRIPCION"=>78,
             "CANTIDAD"=>22,
             "P.U."=>25,
             "IVA"=>20,
             "SUBTOTAL"=>22);
$pdf->addCols( $cols);
$cols=array( "CODIGO"=>"L",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "P.U."=>"R",
             "IVA" =>"R",
             "SUBTOTAL"=>"C");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);
//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 89;

//Obtenemos todos los detalles de la venta actual
$rsptad = $venta->ventaDetalle($_GET["id"]);

while ($regd = $rsptad->fetch_object()) {
  $line = array( "CODIGO"=> "$regd->idmercaderia",
                "DESCRIPCION"=> utf8_decode("$regd->descripcion"),
                "CANTIDAD"=> "$regd->cantidad",
                "P.U."=> "$regd->precio",
                "IVA" => "$regd->iva",
                "SUBTOTAL"=> "$regd->subtotal");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
}

//Convertimos el total en letras
require_once "Letras.php";
$V=new EnLetras(); 
$con_letra=strtoupper($V->ValorEnLetras($regv->total,"GUARANIES"));
$pdf->addCadreTVAs("---".$con_letra);

//Mostramos el impuesto
$pdf->addTVAs( $regd->iva, $regv->total,"Gs/ ");
$pdf->addCadreEurosFrancs("IVA"." $regd->iva %");
$pdf->Output('Reporte de Venta','I');


}else{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
  
?>
