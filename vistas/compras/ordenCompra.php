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
                          <h1 class="box-title">Orden Compra <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Nuevo</button><a data-toggle="modal" href="#myModal2">
                              <button id="btnAgregarPedido" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>
                              Agregar Pedido
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

                          <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>idPedido:</label>
                            <input type="text" name="idpedido" id="idpedido" class="form-control" readonly>
                          </div>
                          
                          <div class="form-group col-lg-7 col-md-7 col-sm-6 col-xs-12">
                            <label>Proveedor(*):</label>
                            <input type="hidden" name="idorden" id="idorden">
                            <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Fecha(*):</label>    
                            <input type="datetime-local" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Personal(*):</label>
                            <input type="text" class="form-control" name="personal" id="personal" value="<?php echo $_SESSION['personal']; ?>" required>
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Sucursal(*):</label>
                            <input type="text" class="form-control" name="sucursal" id="sucursal" value="<?php echo $_SESSION['sucursal']; ?>" required>
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
                                <th>Opciones</th>
                                <th>Id</th>
                                <th>Mercaderia</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                              </thead>
                              <tbody>
                              </tbody>
                              <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>TOTAL</th>
                                <th><h4 id="total">Gs/. 0.00</h4><input type="hidden" name="total_compra" id="total_compra"></th>
                              </tfoot>
                              
                            </table>
                          </div>

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

  <!--Modal Pedidos-->

  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Seleccione Pedido de Compra</h4>
            </div>
            <div class="modal-body">
              <table id="tblpedidos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Id</th>
                  <th>Fecha</th>
                  <th>Personal</th>
                  <th>Sucursal</th>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Id</th>
                  <th>Fecha</th>
                  <th>Personal</th>
                  <th>Sucursal</th>
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
<script src="../scripts/compras/ordenCompra.js"></script>

<?php
  }
  ob_end_flush();
?>