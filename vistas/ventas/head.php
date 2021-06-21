<?php
// if(strlen(session_id()) < 1) DESCOMENTAR
// session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ITVentas | www.incanatoit.com</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../public/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../../public/img/favicon.ico">

    <!--DATATABLES-->
    <link rel="stylesheet" href="../../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../../public/datatables/responsive.dataTables.min.css">
    <link rel="stylesheet" href="../../public/css/bootstrap-select.min.css">
  </head>
  <body class="hold-transition skin-blue-light sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>IT</b>Ventas</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>ITVentas</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $_SESSION['usuario']; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                    <p>
                    Nuñez Repuestos
                      <small>https://adamnunez96.github.io/portfolio/</small>
                    </p>
                  </li>
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    
                    <div class="pull-right">
                      <a href="../../ajax/referenciales/usuario.php?op=salir" class="btn btn-default btn-flat">Cerrar</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">       
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>
            <li>

            <?php 
                if($_SESSION['ESCRITORIO']==1){
                  echo '<a href="escritorio.php">
                    <i class="fa fa-tasks"></i><span>Escritorio</span>
                  </a>';
                }
              ?> 

              <?php 
                if($_SESSION['REFERENCIALES']==1){
                  echo '<li class="treeview">
                          <a href="#"><i class="fa fa-laptop"></i><span>REFERENCIALES</span><i class="fa fa-angle-left pull-right"></i></a>
                          <ul class="treeview-menu">

                            <li class="treeview">
                            <a href="#"><span>Mant. y Seg.</span><i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                              <li><a href="../referenciales/usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                              <li><a href="../referenciales/permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                              <li><a href="../referenciales/sucursal.php"><i class="fa fa-circle-o"></i> Sucursales</a></li>
                              <li><a href="../referenciales/ciudad.php"><i class="fa fa-circle-o"></i> Ciudades</a></li>
                              <li><a href="../referenciales/deposito.php"><i class="fa fa-circle-o"></i> Depositos</a></li>
                              <li><a href="../referenciales/tipoDocumento.php"><i class="fa fa-circle-o"></i> Tipo Documentos</a></li>
                            </ul>
                            </li>
                            <li class="treeview">
                              <a href="#"><span>Compras</span><i class="fa fa-angle-left pull-right"></i></a>
                              <ul class="treeview-menu">
                                <li><a href="../referenciales/mercaderia.php"><i class="fa fa-circle-o"></i> Mercaderias</a></li>
                                <li><a href="../referenciales/marca.php"><i class="fa fa-circle-o"></i> Marcas</a></li>
                                <li><a href="../referenciales/proveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
                                <li><a href="../referenciales/tipoImpuesto.php"><i class="fa fa-circle-o"></i> Tipo de Impuestos</a></li>
                              </ul>
                            </li>
                            <li class="treeview">
                              <a href="#"><span>Ventas</span><i class="fa fa-angle-left pull-right"></i></a>
                              <ul class="treeview-menu">
                              <li><a href="../referenciales/tarjeta.php"><i class="fa fa-circle-o"></i> Tarjetas</a></li>
                              <li><a href="../referenciales/formaPago.php"><i class="fa fa-circle-o"></i> Formas de Pago</a></li>
                              <li><a href="../referenciales/entidadEmisora.php"><i class="fa fa-circle-o"></i> Entidades Emisoras</a></li>
                              <li><a href="../referenciales/caja.php"><i class="fa fa-circle-o"></i> Cajas</a></li>
                              <li><a href="../referenciales/marcaTarjeta.php"><i class="fa fa-circle-o"></i> Marcas Tarjetas</a></li>
                              </ul>
                            </li>
                            <li class="treeview">
                              <a href="#"><span>Servicios</span><i class="fa fa-angle-left pull-right"></i></a>
                              <ul class="treeview-menu">
                                <li><a href="../referenciales/vehiculo.php"><i class="fa fa-circle-o"></i> Vehiculos</a></li>
                                <li><a href="../referenciales/cliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                                <li><a href="../referenciales/personal.php"><i class="fa fa-circle-o"></i> Personales</a></li>
                              </ul>
                            </li>

                          </ul>
                        </li>';
                }
              ?> 

              <?php 
                if($_SESSION['COMPRAS']==1){
                  echo '<li class="treeview">
                          <a href="#">
                            <i class="fa fa-bar-chart"></i> <span>COMPRAS</span>
                            <i class="fa fa-angle-left pull-right"></i>
                          </a>
                          <ul class="treeview-menu">
                            <li><a href="../compras/pedidoCompra.php"><i class="fa fa-circle-o"></i> Pedidos Compra</a></li>
                            <li><a href="../compras/ordenCompra.php"><i class="fa fa-circle-o"></i> Orden Compras</a></li>
                            <li><a href="../compras/facturaCompra.php"><i class="fa fa-circle-o"></i> Facturas Compra</a></li>
                            <li><a href="../compras/ajusteStock.php"><i class="fa fa-circle-o"></i> Ajuste Stock</a></li>
                            <li><a href="../compras/notaRemision.php"><i class="fa fa-circle-o"></i> Nota Remision</a></li>
                            <li><a href="../compras/notaCrediDebi.php"><i class="fa fa-circle-o"></i> Nota Credito/Debito</a></li>  
                            <li><a href="../compras/libroCompra.php"><i class="fa fa-circle-o"></i> Libro Compras</a></li>                
                          </ul>
                        </li>';
                }
              ?>  

              <?php 
                if($_SESSION['VENTAS']==1){
                  echo '<li class="treeview">
                          <a href="#">
                            <i class="fa fa-bar-chart"></i> <span>VENTAS</span>
                            <i class="fa fa-angle-left pull-right"></i>
                          </a>
                          <ul class="treeview-menu">
                            <li><a href="aperturaCierreCaja.php"><i class="fa fa-circle-o"></i> Apertura/Cierre Caja</a></li>
                            <li><a href="facturaVenta.php"><i class="fa fa-circle-o"></i> Facturas Venta</a></li>
                            <li><a href="cobro.php"><i class="fa fa-circle-o"></i> Cobros</a></li>
                            <li><a href="notaCreditoDebito.php"><i class="fa fa-circle-o"></i> Nota Credito/Debito</a></li>
                            <li><a href="arqueo.php"><i class="fa fa-circle-o"></i> Arqueo</a></li>
                            <li><a href="recaudaciones.php"><i class="fa fa-circle-o"></i> Recaudaciones a Depositar</a></li>
                            <li><a href="libroVenta.php"><i class="fa fa-circle-o"></i> Libro Ventas</a></li>                
                          </ul>
                        </li>';
                }
              ?>  

              <?php 
                if($_SESSION['SERVICIOS']==1){
                  echo '<li class="treeview">
                          <a href="#">
                            <i class="fa fa-folder"></i> <span>SERVICIOS</span>
                            <i class="fa fa-angle-left pull-right"></i>
                          </a>
                          <ul class="treeview-menu">
                          <li><a href="../servicios/recepcion.php"><i class="fa fa-circle-o"></i> Recepcion Motocicleta</a></li>
                          <li><a href="../servicios/diagnostico.php"><i class="fa fa-circle-o"></i> Diagnostico Motocicleta</a></li>
                          <li><a href="../servicios/presupuesto.php"><i class="fa fa-circle-o"></i> Presupuesto Servicio</a></li>
                          <li><a href="../servicios/ordenTrabajo.php"><i class="fa fa-circle-o"></i> Orden de Trabajo</a></li>
                          <li><a href="../servicios/servicio.php"><i class="fa fa-circle-o"></i> Servicios</a></li>
                          </ul>
                        </li>';
                }
              ?>  

              <?php 
                if($_SESSION['REPORTES']==1){
                  echo '<li class="treeview">
                          <a href="#">
                            <i class="fa fa-shopping-cart"></i>
                            <span>REPORTES</span>
                            <i class="fa fa-angle-left pull-right"></i>
                          </a>
                          <ul class="treeview-menu">
                            <li><a href="../venta.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
                            <li><a href="../cliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                          </ul>
                        </li>  ';
                }
              ?>    
              
              <?php 
                if($_SESSION['ADMINISTRADOR']==1){
                  echo '<li class="treeview">
                          <a href="#">
                            <i class="fa fa-folder"></i> <span>ACCESOS</span>
                            <i class="fa fa-angle-left pull-right"></i>
                          </a>
                          <ul class="treeview-menu">
                            <li><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                            <li><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                          </ul>
                        </li>';
                }
              ?>  

            <li>
              <a href="#">
                <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                <small class="label pull-right bg-red">PDF</small>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
                <small class="label pull-right bg-yellow">IT</small>
              </a>
            </li>
                        
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>