<?php

    // activamos el almacenamiento en el buffer
    ob_start();
    session_start();
  
    if(!isset($_SESSION["usuario"])){
      header("Location: login.html");
    }else{
        require "head.php";
      if($_SESSION['ESCRITORIO']==1){
        
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
                          <h1 class="box-title">Escritorio </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h4 style="font-size:17px;">
                                        <strong>S/ <?php echo $totalc; ?></strong>
                                    </h4>
                                    <p>Compras</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="ingreso.php" class="small-box-footer">Compras <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h4 style="font-size:17px;">
                                        <strong>S/ <?php echo $totalv; ?></strong>
                                    </h4>
                                    <p>Ventas</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="venta.php" class="small-box-footer">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">Compras de los ultimos 10 dias</div>
                            </div>
                            <div class="box-body">
                                <canvas id="compras" width="400" height="300"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">Ventas de los ultimos 12 meses</div>
                            </div>
                            <div class="box-body">
                                <canvas id="ventas" width="400" height="300"></canvas>
                            </div>
                        </div>
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
}
  ob_end_flush();
?>

