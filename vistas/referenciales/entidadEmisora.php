<?php
// activamos el almacenamiento en el buffer
ob_start();
session_start();

if(!isset($_SESSION["usuario"])){
  header("Location: ../login.html");
}else{
  require 'head.php';
  if($_SESSION['REFERENCIALES']==1){
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
                          <h1 class="box-title">Entidades Emisoras <button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">

                          <thead>
                            <th>Id</th>
                            <th>Descripcion</th>
                            <th>Telefono</th>
                            <th>Direccion</th>
                            <th>Opciones</th>
                          </thead>

                          <tbody>
                          </tbody>

                          <tfoot>
                            <th>Id</th>
                            <th>Descripcion</th>
                            <th>Telefono</th>
                            <th>Direccion</th>
                            <th>Opciones</th>
                          </tfoot>
                        
                        </table>

                    </div>
                    <div class="panel-body table-responsive"  id="formularioregistros">
                        <form name="formulario" id="formulario" action="" method="post">

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Descripcion(*)</label>
                            <input type="hidden" name="identidademisora" id="identidademisora">
                            <input type="text" class="form-control" name="descripcion" id="descripcion" onblur="mayuscula(this)" maxlength="30" placeholder="Nombre de la Entidad Emisora" required>
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Telefono</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="30" placeholder="Telefono">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Direccion</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" onblur="mayuscula(this)" maxlength="80" placeholder="Direccion">
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
<script src="../scripts/referenciales/entidadEmisora.js"></script>

<?php
  }
  ob_end_flush();
?>