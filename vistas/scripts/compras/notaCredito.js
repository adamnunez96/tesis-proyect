var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    });

    //cargamos los items al select proveedor
    $.post("../../ajax/compras/notaCredito.php?op=selectProveedor", function(r){
        $("#idproveedor").html(r);
        $("#idproveedor").selectpicker('refresh');
        $("#idproveedor").val('1');
        $("#idproveedor").selectpicker('refresh');
    });

    //cargamos los items al select deposito
    $.post("../../ajax/compras/notaCredito.php?op=selectDeposito", function(r){
        $("#iddeposito").html(r);
        $("#iddeposito").selectpicker('refresh');
        $("#iddeposito").val('1');
        $("#iddeposito").selectpicker('refresh');
    });

    //cargamos los items al select tipo documento
    $.post("../../ajax/compras/notaCredito.php?op=selectTipoDocumento", function(r){
        $("#idtipodoc").html(r);
        $("#idtipodoc").selectpicker('refresh');
        $("#idtipodoc").val('4');
        $("#idtipodoc").selectpicker('refresh');
    });

}

//funcion limpiar
function limpiar(){

    $("#idcompra").val("");
    $("#nroFactCredito").val("");
    $("#timbrado").val("");
    $("#obs").val("");
    $("#fecha_hora").val("");
    
    $(".filas").remove();
    $(".resul").html("");
    $("#total").html("Gs./0.00");
    $("#total2").html("");
    $("#total_iva5").val("0");
    $("#total_iva10").val("0");
    $("#total_exenta").val("0");
    $("#liq_iva5").val("0");
    $("#liq_iva10").val("0");

    //obtenemos la fecha y hora actual
    var now = new Date();
    var day = ("0" + (now.getDate())).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var year = now.getFullYear();
    // var hour = ("0" + (now.getHours())).slice(-2);
    // var minute = ("0" + (now.getMinutes())).slice(-2);
    // var second = ("0" + (now.getSeconds())).slice(-2);
    // var nowHour = `${hour}:${minute}:${second}`;
    // var today = now.getFullYear()+"-"+(month)+"-"+(day)+"T"+nowHour;
    var today = `${year}-${month}-${day}`;
    // $('#fecha_hora').attr({
    //     "min": `${year}-${month}-${day}T${nowHour}`,
    //     "max": `${year}-${month}-${day}T${nowHour}`
    // });
    $('#fecha_hora').val(today);
    

}

//funcion mostrar formulario
function mostrarform(flag){
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        listarMercaderias();
        listarCompras();
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles=0;
        $("#btnAgregarArt").show();
        $("#btnAgregarCompra").show();
        $(".ivas").show();
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnAgregarCompra").hide();
    }
}

//funcion carcelar form
function cancelarform(){
    limpiar();
    mostrarform(false);
}

//funcion listar
function listar(){
    tabla=$('#tbllistado').dataTable({
        "aProcessing": true, //ativamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',//definimos los elementos del control de tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax":
                {
                    url: '../../ajax/compras/notaCredito.php?op=listar',
                    type: "get",
                    dataType: "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 10, //paginacion (cada cuanto registros vamos a pagina)
        "order": [[0, "desc"]]

    }).DataTable();
}

//funcion listar mercaderias a cargar en el detalle
function listarMercaderias(){

    tabla=$('#tblarticulos').dataTable({
        "aProcessing": true, //ativamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',//definimos los elementos del control de tabla
        buttons: [
        
        ],
        "ajax":
                {
                    url: '../../ajax/compras/notaCredito.php?op=listarMercaderias',
                    type: "get",
                    dataType: "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 5, //paginacion (cada cuanto registros vamos a pagina)
        "order": [[0, "desc"]]

    }).DataTable();
}

//funcion listar pedidos para cargar para la orden de compra
function listarCompras(){

    tabla=$('#tblcompras').dataTable({
        "aProcessing": true, //ativamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',//definimos los elementos del control de tabla
        buttons: [
        
        ],
        "ajax":
                {
                    url: '../../ajax/compras/notaCredito.php?op=listarCompras',
                    type: "get",
                    dataType: "json",
                    error: function(e){
                        console.log(e.responseText);
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 5, //paginacion (cada cuanto registros vamos a pagina)
        "order": [[0, "desc"]]

    }).DataTable();
}


// funcion para guardar
function guardaryeditar(e){
    e.preventDefault(); //no se activara la accion predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../../ajax/compras/notaCredito.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos){
            bootbox.alert(datos); //aca recibimos el mensaje de mi categoria.php que esta en ajax
            mostrarform(false);
            listar();
        }

    });
    limpiar();

}

function mostrar(idcredito){

    $("#idpersonal").val("");
    $("#idsucursal").val("");
    $("#fecha_hora").val("");

    $.post("../../ajax/compras/notaCredito.php?op=mostrar",{idcredito : idcredito}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcredito").val(data.idnotacredidebi);
        $("#idcompra").val(data.idcompra);
        $("#nroFactCredito").val(data.nro_nota_credito_debito);
        $("#timbrado").val(data.timbrado);
        $("#idproveedor").val(data.idproveedor);
        $("#idproveedor").selectpicker('refresh');
        $("#idpersonal").val(data.personal);
        $("#idsucursal").val(data.sucursal);
        $("#iddeposito").val(data.iddeposito);
        $("#iddeposito").selectpicker('refresh');
        $("#idtipodoc").val(data.idtipodocumento);
        $("#idtipodoc").selectpicker('refresh');
        $("#fecha_hora").val(data.fecha);
        $("#obs").val(data.obs);
        $("#montoTotal").val(data.monto);

        //ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
        $("#btnAgregarPedido").hide();
    });
    $.post("../../ajax/compras/notaCredito.php/?op=listarDetalle&id="+idcredito, function(res){
        $(".ivas").hide();
        $("#detalles").html(res);
    });
}

function agregarCompra(idcompra){

    $.post("../../ajax/compras/notaCredito.php?op=listarCabeceraCompra",{idcompra : idcompra}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcompra").val(data.idcompra);
        $("#idproveedor").val(data.idproveedor);
        $("#idproveedor").selectpicker('refresh');

        //ocultar y mostrar los botones
        $("#btnCancelar").show();

    });
    $.post("../../ajax/compras/notaCredito.php/?op=listarDetalleCompra&id="+idcompra, function(res){
        let data = JSON.parse(res);
        mostrarDetalle(data);
    });
}

//funciona para desactivar registros
function anular(idcredito, idcompra){
    bootbox.confirm("Estas Seguro de anular la Nota de Credito?", function(result){
        if(result){
            $.post("../../ajax/compras/notaCredito.php?op=anular", {idcredito: idcredito, idcompra : idcompra}, function(e){
                tabla.ajax.reload();
                //bootbox.alert(e);
            });
        }
    });
}

//declaracion de variables necesarias para trabajar con las compras y sus detalles

var cont=0;
var detalles=0;
$("#guardar").hide();
$("#btnGuardar").hide();


function agregarDetalle(idmercaderia, descripcion, preciocompra, tipoimpuesto){
    var cantidad=1;
    //var precio=1;
    // var precio_venta=1;

    if(idmercaderia != ""){
        var subtotal=cantidad*preciocompra;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idmercaderia[]" value="'+idmercaderia+'">'+idmercaderia+'</td>'+
        '<td><input type="hidden" name="descripcion[]" value="'+descripcion+'">'+descripcion+'</td>'+
        '<td><input type="number" name="cantidad[]" id="cantidad[]" style="width:80px" value="'+cantidad+'"></td>'+
        '<td><input type="hidden" name="preciocompra[]" id="preciocompra[]" value="'+preciocompra+'">'+preciocompra+'</td>'+
        '<td><span class="iva[]" id="iva[]" data-value="'+tipoimpuesto+'">'+tipoimpuesto+'</span></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
        '<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        modificarSubtotales();
    }else{
        alert("error al ingresar el detalle, revisar los datos de la mercaderia");
    }
}

function mostrarDetalle(data){

    data.forEach(detalle =>{
        var subtotal=detalle[3]*detalle[4];
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idmercaderia[]" value="'+detalle[1]+'">'+detalle[1]+'</td>'+
        '<td><input type="hidden" name="descripcion[]" value="'+detalle[2]+'">'+detalle[2]+'</td>'+
        '<td><input type="number" name="cantidad[]" style="width:80px" value="'+detalle[3]+'"></td>'+
        '<td><input type="hidden" name="preciocompra[]" value="'+detalle[4]+'">'+detalle[4]+'</td>'+
        '<td><span class="iva[]" id="iva[]" data-value="'+detalle[5]+'">'+detalle[5]+'</span></td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
        '<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        modificarSubtotales();
    });  
}

function modificarSubtotales(){
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("preciocompra[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i < cant.length; i++) {
        var inpC=cant[i];
        var inpP=prec[i];
        var inpS=sub[i];
       
        // aca calculamos el subtotal de la cantidad por el precio
        inpS.value = inpC.value * inpP.value;
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;

    }
    calcularTotales();

}

function calcularTotales(){
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for(var i = 0; i < sub.length; i++){
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("Gs/. " + total);
    $("#total_compra").val(total);
    calcularIva();
    evaluar();
}

function calcularIva(){

    let sub = document.getElementsByName("subtotal");
    let iva = document.getElementsByClassName("iva[]");

    let iva5 = 0;
    let iva10 = 0;
    let exenta = 0;
    let liq5 = 0;
    let liq10 = 0;

    for(let i = 0; i < sub.length; i++){
        let inpI = iva[i];

        if(inpI.dataset.value == 10){
            liq10 += (document.getElementsByName("subtotal")[i].value * 10)/100;
            iva10 += document.getElementsByName("subtotal")[i].value;
        }else if(inpI.dataset.value == 5){
            liq5 += (document.getElementsByName("subtotal")[i].value * 5)/100;
            iva5 += document.getElementsByName("subtotal")[i].value;
        }else{
            exenta += document.getElementsByName("subtotal")[i].value;
        }
    }

    document.getElementById("total_iva10").value = iva10 - liq10;
    document.getElementById("liq_iva10").value = liq10;
    document.getElementById("total_iva5").value = iva5 - liq5;
    document.getElementById("liq_iva5").value = liq5;
    document.getElementById("total_exenta").value = exenta;
    
}

function evaluar(){
    if(detalles>0){
        $("#btnGuardar").show();
    }else{
        $("#btnGuardar").hide();
        cont=0;
    }
}

function eliminarDetalle(indice){
    $("#fila" + indice).remove();
    calcularTotales()
    detalles = detalles-1;
    //console.log(detalles);
    evaluar();
}

init();