<?php 
//incluimos inicialmente la conexion a la base de datos
require "../../config/conexion.php";

class AperturaCierreCaja{
    //implementamos nuestro constructor
    public function __construct(){

    }

    //implementamos un metodo para insertar registros (la apertura)
    public function insertar($idpersonal, $idsucursal, $idcaja, $fecha_apertura, $monto_apertura){
        $fecha =  date('Y-m-d h:i:s', time());
        $sql = "INSERT INTO apertura_cierre_caja (idpersonal, idsucursal, idcaja, fechaapertura, montoapertura, estado) 
        VALUES ('$idpersonal', '$idsucursal', '$idcaja', (SELECT NOW()), '$monto_apertura', '1')";

        return ejecutarConsulta($sql);
    }

    //implementamos un metodo para el cierre de caja
    public function cierre($idapertura){
        try {
            $monto_cierre = $this->recuperarMontos($idapertura);
            //print_r($monto_cierre);
            //$total = intval($monto_cierre);
            $sql = "UPDATE apertura_cierre_caja set estado = '0', fechacierre = (SELECT NOW()), montocierre = '$monto_cierre' where idaperturacierre = '$idapertura'";
            return ejecutarConsulta($sql);
        } catch (\Throwable $th) {
            throw "error en la funcion cierre: ". $th;
        }
        

    }

    //implementar un metodo para listar los registros
    public function listar($idsucursal){
        $sql = "SELECT ac.idaperturacierre, ac.idcaja, c.descripcion as caja, ac.idpersonal, concat(p.nombre, ' ', p.apellido) AS personal, ac.idsucursal, s.descripcion as sucursal, ac.fechaapertura, ac.montoapertura, ac.fechacierre, ac.montocierre, ac.estado 
        FROM apertura_cierre_caja ac JOIN cajas c ON ac.idcaja = c.idcaja JOIN personales p ON ac.idpersonal = p.idpersonal 
        JOIN sucursales s ON ac.idsucursal = s.idsucursal WHERE ac.idsucursal = '$idsucursal' ORDER BY ac.idaperturacierre DESC";
        return ejecutarConsulta($sql);
    }

    public function validarCaja($fecha_hora, $idsucursal, $idcaja){
        $fecha = strtotime($fecha_hora);
        $fecha = date('Y-m-d', $fecha);
        $sql = "SELECT * FROM apertura_cierre_caja where estado = '1' and idsucursal = '$idsucursal' and fechaapertura >= DATE($fecha_hora) and idcaja = '$idcaja'";
        //print_r($sql);
        $resultado = ejecutarConsulta($sql);
        return mysqli_num_rows($resultado);
    }

    public function recuperarMontos($idapertura){
        try {
            $sql = "SELECT SUM(monto) as monto from (
                SELECT montoapertura as monto FROM apertura_cierre_caja where idaperturacierre  = '$idapertura'
                UNION
                SELECT COALESCE(SUM(ct.monto), 0) as monto from cobros_tarjeta ct JOIN cobros c ON ct.idcobro = c.idcobro JOIN apertura_cierre_caja a ON c.idaperturacierre = a.idaperturacierre WHERE a.idaperturacierre = '$idapertura'
                UNION
                SELECT COALESCE(SUM(cc.monto), 0) as monto from cobros_cheque cc JOIN cobros c ON cc.idcobro = c.idcobro JOIN apertura_cierre_caja a ON c.idaperturacierre = a.idaperturacierre WHERE a.idaperturacierre = '$idapertura'
                UNION 
                SELECT COALESCE(SUM(c.montoefectivo), 0) as monto FROM cobros c JOIN apertura_cierre_caja a ON c.idaperturacierre = a.idaperturacierre WHERE a.idaperturacierre = '$idapertura') as total";
                $total = 0;
                if($rspta = ejecutarConsulta($sql)){
                    if($fila = $rspta->fetch_object()){
                        $total = $fila->monto;
                    }
                };
                print_r($total);
            return $total;
        } catch (Exception $e) {
            echo "error: ". $e->getMessage();
        }
        
    }

}
?>