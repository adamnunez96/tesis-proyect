<?php
// activamos el almacenamiento en el buffer
ob_start();
session_start();

if(!isset($_SESSION["usuario"])){
  header("Location: ../login.html");
}else{
  require 'head.php';
  if($_SESSION['VENTAS']==1){
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
                          <h1 class="box-title">Apertura/Cierre Caja <button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">

                          <thead>
                            <th>Nro Apertura</th>
                            <th>Caja</th>
                            <th>Personal</th>
                            <th>Fecha Apertura</th>
                            <th>Monto Apertura</th>
                            <th>Fecha Cierre</th>
                            <th>Monto Cierre</th>
                            <th>Sucursal</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </thead>

                          <tbody>
                          </tbody>
                        
                        </table>

                    </div>
                    <div class="panel-body table-responsive"  id="formularioregistros">
                        <form name="formulario" id="formulario" action="" method="post">

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Personal(*):</label>
                            <input type="hidden" name="idapertura" id="idapertura">
                            <input type="text" class="form-control" name="personal" id="personal" value="<?php echo $_SESSION['personal']; ?>" required readonly>
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Sucursal(*):</label>
                            <input type="text" class="form-control" name="sucursal" id="sucursal" value="<?php echo $_SESSION['sucursal']; ?>" required readonly>
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Fecha(*):</label>    
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Monto Apertura(*)</label>                      
                            <input type="number" class="form-control" name="montoApertura" id="montoApertura" min="1" pattern="^[0-9]+" onkeypress="return valideKey(event);" required>
                          </div>

                          <div class="form-group col-lg-6 col-md-3 col-sm-3 col-xs-12">
                            <label>Caja(*):</label>
                            <select name="idcaja" id="idcaja" class="form-control selectpicker" required>
                            </select>
                          </div>                          

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
<?php
}else{
  require 'noacceso.php';
}
  require 'footer.php';
?>
<script src="../scripts/ventas/aperturaCierreCaja.js"></script>

<?php
  }
  ob_end_flush();
?>