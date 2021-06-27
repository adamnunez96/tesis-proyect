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
                          <h1 class="box-title">Libro Compras </h1>
                        <div class="box-tools pull-right"></div>
                    </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                            </div>

                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                    <th>Id</th>
                                    <th>Nro Factura</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Monto Pagado</th>
                                    <th>Monto IVA 5</th>
                                    <th>Liq IVA 5</th>
                                    <th>Monto IVA 10</th>
                                    <th>Liq IVA 10</th>
                                    <th>Monto Exenta</th>
                                </thead>
                                <tbody>
                                </tbody>
                                
                                </tfoot>
                                </table>
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
<script src="../scripts/compras/libroCompra.js"></script>

<?php
  }
  ob_end_flush();
?>