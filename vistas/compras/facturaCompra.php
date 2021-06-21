<?php
  //activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if(!isset($_SESSION["usuario"])){
    header("Location: ../login.html");
  }else{
    require 'head.php';
    if($_SESSION['COMPRAS']==1){

?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Facturas Compra <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> 
                          
                          
                          Nuevo</button><a data-toggle="modal" href="#myModal2">
                              <button id="btnAgregarPedido" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>
                              Agregar Orden Compra
                              </button>
                            </a></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Id</th>
                            <th>Nro Factura</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Personal</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <th>Id</th>
                            <th>Nro Factura</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Personal</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </tfoot>
                        </table>
                    </div>

                    <div class="panel-body table-responsive"  id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">

                          <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-12">
                            <label>idOrden:</label>
                            <input type="text" name="idorden" id="idorden" class="form-control" readonly>
                          </div>
                          
                          <div class="form-group col-lg-7 col-md-7 col-sm-8 col-xs-12">
                            <label>Proveedor(*):</label>
                            <input type="hidden" name="idcompra" id="idcompra">
                            <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-12">
                            <label>Fecha(*):</label>    
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-8 col-xs-12">
                            <label>Nro de Factura(*):</label>
                            <input type="text" class="form-control" name="nrofactura" id="nrofactura" placeholder="Numero de Factura" required>
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Personal(*):</label>
                            <input type="text" class="form-control" name="idpersonal" id="idpersonal" value="<?php echo $_SESSION['personal']; ?>" required readonly>
                          </div>

                          <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label>Sucursal(*):</label>
                            <input type="text" class="form-control" name="idsucursal" id="idsucursal" value="<?php echo $_SESSION['sucursal']; ?>" required readonly>
                          </div>

                          <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label>Deposito(*):</label>
                            <select name="iddeposito" id="iddeposito" class="form-control selectpicker" required>
                            </select>
                          </div>

                          <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label>Tipo Documento(*):</label>
                            <select name="idtipodoc" id="idtipodoc" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label>Forma de Pago(*):</label>
                            <select name="idformapago" id="idformapago" class="form-control selectpicker" required>
                            </select>
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label>Cuotas:</label>
                            <select name="cuota" id="cuota" class="form-control selectpicker">
                              <option value="1">1</option>
                              <option value="2" disabled>2</option>
                              <option value="4" disabled>4</option>
                              <option value="6" disabled>6</option>
                              <option value="8" disabled>8</option>
                              <option value="10" disabled>10</option>
                              <option value="12" disabled>12</option>
                            </select>
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Observacion:</label>
                            <input type="text" class="form-control" name="obs" id="obs" maxlenghth="100" placeholder="Observacion">
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <a data-toggle="modal" href="#myModal">
                              <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>
                              Agregar Mercaderia
                              </button>
                            </a>
                          </div>

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                <th style="width:30px">Opciones</th>
                                <th>Id</th>
                                <th>Mercaderia</th>
                                <th style="width:80px">Cantidad</th>
                                <th>Precio</th>
                                <th style="width:40px">Iva</th>
                                <th>Subtotal</th>
                              </thead>
                              <tbody>
                              </tbody>
                              <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                              </tfoot>
                              <div id="detalle" style="display:none" data-value="<?php echo $_SESSION['detalle']; ?>"></div>
                            </table>
                          </div>

                          <!--este especio es para el calculo de los ivas-->
                          
                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-12">
                              <label>TOTAL sin IVA 5%:</label>
                              <input type="text" name="total_iva5" id="total_iva5" class="form-control text-center borde" placeholder="0" readonly>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-12">
                              <label>TOTAL sin IVA 10%:</label>
                              <input type="text" name="total_iva10" id="total_iva10" class="form-control text-center borde" placeholder="0" readonly>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-12">
                              <label>TOTAL EXENTA:</label>
                              <input type="text" name="total_exenta" id="total_exenta" class="form-control text-center borde" placeholder="0" readonly>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-12">
                              <label>Liq. IVA 5%:</label>
                              <input type="text" name="liq_iva5" id="liq_iva5" class="form-control text-center borde" placeholder="0" readonly>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-12">
                              <label>Liq. IVA 10%:</label>
                              <input type="text" name="liq_iva10" id="liq_iva10" class="form-control text-center borde" placeholder="0" readonly>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                <label>TOTAL: </label><h4 id="total" class="form-control borde text-center">Gs/.0.00</h4><input type="hidden" name="total_compra" id="total_compra">
                            </div>
                          
                          
                          <!--hasta aca-->
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  
  <!--Modal Mercaderia-->

      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Seleccione Mercaderia</h4>
            </div>
            <div class="modal-body">
              <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Id</th>
                  <th>Mercaderia</th>
                  <th>Marca</th>
                  <th>Precio Compra</th>
                  <th>Imagen</th>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Id</th>
                  <th>Mercaderia</th>
                  <th>Marca</th>
                  <th>Precio Compra</th>
                  <th>Imagen</th>
                </tfoot>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>


  <!--Fin Modal-->

  <!--Modal Ordenes0-->

  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Seleccione Orden de Compra</h4>
            </div>
            <div class="modal-body">
              <table id="tblordenes" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Id</th>
                  <th>Fecha</th>
                  <th>Proveedor</th>
                  <th>Personal</th>
                  <th>Monto</th>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Id</th>
                  <th>Fecha</th>
                  <th>Proveedor</th>
                  <th>Personal</th>
                  <th>Monto</th>
                </tfoot>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>


  <!--Fin Modal-->

<?php
}else{
  require 'noacceso.php';
}
  require 'footer.php';
?>
<script src="../scripts/compras/facturaCompra.js"></script>

<?php
  }
  ob_end_flush();
?>