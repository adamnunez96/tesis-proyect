<?php
  //activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if(!isset($_SESSION["usuario"])){
    header("Location: ../login.html");
  }else{
    require 'head.php';
    if($_SESSION['SERVICIOS']==1){

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
                          <h1 class="box-title">Diagnosticos <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Nuevo</button>
                            <a data-toggle="modal" href="#myModal2">
                              <button id="btnAgregarRecepcion" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>
                                Agregar Recepcion
                              </button>
                            </a>
                          </h1>
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
                            <th>Cliente</th>
                            <th>Personal</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </thead>
                          <tbody></tbody>

                        </table>
                    </div>

                    <div class="panel-body table-responsive"  id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">

                         <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>idRecepcion:</label>
                            <input type="text" name="idrecepcion" id="idrecepcion" class="form-control" readonly>
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Cliente(*):</label>
                            <input type="hidden" name="iddiagnostico" id="iddiagnostico">
                            <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>

                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha(*):</label>    
                            <input type="datetime-local" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Personal(*):</label>
                            <input type="text" class="form-control" name="idpersonal" id="idpersonal" value="<?php echo $_SESSION['personal']; ?>" required readonly>
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Sucursal(*):</label>
                            <input type="text" class="form-control" name="idsucursal" id="idsucursal" value="<?php echo $_SESSION['sucursal']; ?>" required readonly>
                          </div>
                          
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Observacion:</label>
                            <input type="text" class="form-control" name="obs" id="obs" maxlenghth="100" placeholder="Observacion">
                          </div>

                          
                          <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <a data-toggle="modal" href="#myModal">
                              <button id="btnAgregarVehiculo" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>
                              Agregar Vehiculo
                              </button>
                            </a>
                          </div>

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                <th style="width:30px">Opciones</th>
                                <th>Id</th>
                                <th>Vehiculo</th>
                                <th>Chapa</th>
                              </thead>
                              <tbody>
                              </tbody>
                              
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
  
  <!--Modal Vehiculo-->

      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Seleccione Vehiculo</h4>
            </div>
            <div class="modal-body">
              <table id="tblvehiculos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th style="width:30px">Opciones</th>
                  <th>Id</th>
                  <th>Vehiculo</th>
                  <th>Chapa</th>
                </thead>
                <tbody>
                
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

  <!--Fin Modal-->

  <!--Modal Recepciones-->

  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Seleccione Recepcion</h4>
            </div>
            <div class="modal-body">
              <table id="tblrecepciones" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th style="width:30px">Opciones</th>
                  <th>Id</th>
                  <th>Fecha</th>
                  <th>Cliente</th>
                </thead>
                <tbody>
                
                </tbody>

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
<script src="../scripts/servicios/diagnostico.js"></script>

<?php
  }
  ob_end_flush();
?>