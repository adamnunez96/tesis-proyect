var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    });

    //cargamos los items al select proveedor
    $.post("../../ajax/servicios/ordenTrabajo.php?op=selectCliente", function(r){
        $("#idcliente").html(r);
        $("#idcliente").selectpicker('refresh');
        $("#idcliente").val('1');
        $("#idcliente").selectpicker('refresh');
    });

}

//funcion limpiar
function limpiar(){

    $("#obs").val("");
    $("#fecha_hora").val("");
    $("#idpresupuesto").val("");
    $(".filas").remove();
    $("#total").html("Gs./0.00");

    //obtenemos la fecha y hora actual
    var now = new Date();
    var day = ("0" + (now.getDate())).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var year = now.getFullYear();
    var hour = ("0" + (now.getHours())).slice(-2);
    var minute = ("0" + (now.getMinutes())).slice(-2);
    var second = ("0" + (now.getSeconds())).slice(-2);
    var nowHour = `${hour}:${minute}:${second}`;
    var today = `${year}-${month}-${day}T${nowHour}`;
    $('#fecha_hora').attr({
        "min": `${year}-${month}-${day}T${nowHour}`,
        "max": `${year}-${month}-${day}T${nowHour}`
    });
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
        listarVehiculos(); 
        listarPresupuestos();  
        listarMercaderias();
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles=0;
        $("#btnAgregarVehiculo").show();
        $("#btnAgregarPresupuesto").show();
        $("#btnMercaderia").show();
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnAgregarPresupuesto").hide();
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
                    url: '../../ajax/servicios/ordenTrabajo.php?op=listar',
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
function listarVehiculos(){

    tabla=$('#tblvehiculos').dataTable({
        "aProcessing": true, //ativamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',//definimos los elementos del control de tabla
        buttons: [
        
        ],
        "ajax":
                {
                    url: '../../ajax/servicios/ordenTrabajo.php?op=listarVehiculos',
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

function listarPresupuestos(){

    tabla=$('#tblPresupuestos').dataTable({
        "aProcessing": true, //ativamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',//definimos los elementos del control de tabla
        buttons: [
        
        ],
        "ajax":
                {
                    url: '../../ajax/servicios/ordenTrabajo.php?op=listarPresupuestos',
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

//funcion listar mercaderias a cargar en el detalle
function listarMercaderias(){

    tabla=$('#tblmercaderias').dataTable({
        "aProcessing": true, //ativamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',//definimos los elementos del control de tabla
        buttons: [
        
        ],
        "ajax":
                {
                    url: '../../ajax/servicios/ordenTrabajo.php?op=listarMercaderias',
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

// funcion para guardar o editar
function guardaryeditar(e){
    e.preventDefault(); //no se activara la accion predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../../ajax/servicios/ordenTrabajo.php?op=guardaryeditar",
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

function mostrar(idorden){

    $("#idpersonal").val("");
    $("#idsucursal").val("");
    $("#fecha_hora").val("");

    $.post("../../ajax/servicios/ordenTrabajo.php?op=mostrar",{idorden : idorden}, function(data, status){
        //console.log(data);
        data = JSON.parse(data);
        mostrarform(true);

        $("#idpresupuesto").val(data.idpresupuesto);
        $("#idorden").val(data.idorden);
        $("#idcliente").val(data.idcliente);
        $("#idcliente").selectpicker('refresh');
        $("#idpersonal").val(data.personal);
        $("#idsucursal").val(data.sucursal);
        $("#fecha_hora").val(data.fecha);
        $("#obs").val(data.obs);

        //ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarVehiculo").hide();
        $("#btnMercaderia").hide();
        $("#btnAgregarPresupuesto").hide();
    });
    $.post("../../ajax/servicios/ordenTrabajo.php/?op=listarDetalleVehiculo&id="+idorden, function(r){
        $("#detalles").html(r);
    });
    $.post("../../ajax/servicios/ordenTrabajo.php/?op=listarDetalleManoObra&id="+idorden, function(r){
        $("#detallesMercaderia").html(r);
    });

}

function agregarPresupuesto(idpresupuesto){
    $.post("../../ajax/servicios/ordenTrabajo.php?op=listarCabeceraPresupuesto",{idpresupuesto : idpresupuesto}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);
        $("#idpresupuesto").val(data.idpresupuestoservicio);

        //ocultar y mostrar los botones
        $("#btnCancelar").show();

    });
    $.post("../../ajax/servicios/ordenTrabajo.php/?op=listarDetallePresupuesto&id="+idpresupuesto, function(res){
        let data = JSON.parse(res);
        mostrarDetalle(data);
    });
    $.post("../../ajax/servicios/ordenTrabajo.php/?op=listarDetallePresupuesto2&id="+idpresupuesto, function(res){
        let data = JSON.parse(res);
        mostrarDetalle2(data);
    });
}

//declaracion de variables necesarias para trabajar con las compras y sus detalles

var cont=0;
var detalles=0;
$("#guardar").hide();
$("#btnGuardar").hide();

//funciona para desactivar registros
function anular(idorden){
    bootbox.confirm("Estas Seguro de anular el Presupuesto?", function(result){
        if(result){
            $.post("../../ajax/servicios/ordenTrabajo.php?op=anular", {idorden : idorden}, function(e){
                tabla.ajax.reload();
            });    
        }
    });
}

function agregarVehiculo(idvehiculo, vehiculo, chapa){
    if(idvehiculo != ""){
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idvehiculo[]" value="'+idvehiculo+'">'+idvehiculo+'</td>'+
        '<td><input type="hidden" name="vehiculo[]" value="'+vehiculo+'">'+vehiculo+'</td>'+
        '<td><input type="hidden" name="chapa[]" id="chapa" value="'+chapa+'">'+chapa+'</td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        evaluar();
    }else{
        alert("error al ingresar el detalle, revisar los datos de la mercaderia");
    }
}

//obs: aca le llamamos agregar mercaderia, pero en compras le llamamos agregar detalle
function agregarMercaderia(idmercaderia, descripcion, precioventa){
    var cantidad=1;

    if(idmercaderia != ""){
        var subtotal=cantidad*precioventa;
        let fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idmercaderia[]" value="'+idmercaderia+'">'+idmercaderia+'</td>'+
        '<td><input type="hidden" name="descripcion[]" value="'+descripcion+'">'+descripcion+'</td>'+
        '<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input type="hidden" name="precioventa[]" id="precioventa[]" value="'+precioventa+'">'+precioventa+'</td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
        '<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detallesMercaderia').append(fila);
        modificarSubtotales();
    }else{
        alert("error al ingresar el detalle, revisar los datos de la mercaderia");
    }
}

function mostrarDetalle(data){
    data.forEach(detalle =>{
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idvehiculo[]" value="'+detalle.idvehiculo+'">'+detalle.idvehiculo+'</td>'+
        '<td><input type="hidden" name="vehiculo[]" value="'+detalle.vehiculo+'">'+detalle.vehiculo+'</td>'+
        '<td><input type="hidden" name="chapa[]" id="chapa" value="'+detalle.chapa+'">'+detalle.chapa+'</td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        //console.log(fila);
        $('#detalles').append(fila);
        evaluar();
    });  
}

function mostrarDetalle2(data){
    data.forEach(detalle =>{
        var subtotal=detalle.cantidad*detalle.precio;
        //console.log(detalle.precio);
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idmercaderia[]" value="'+detalle.idmercaderia+'">'+detalle.idmercaderia+'</td>'+
        '<td><input type="hidden" name="descripcion[]" value="'+detalle.mercaderia+'">'+detalle.mercaderia+'</td>'+
        '<td><input type="number" name="cantidad[]" style="width:80px" value="'+detalle.cantidad+'"></td>'+
        '<td><input type="hidden" name="precioventa[]" value="'+detalle.precio+'">'+detalle.precio+'</td>'+
        '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
        '<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        //console.log(fila);
        $('#detallesMercaderia').append(fila);
        modificarSubtotales();
    });  
}

function modificarSubtotales(){
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precioventa[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i < cant.length; i++) {
        var inpC=cant[i];
        var inpP=prec[i];
        var inpS=sub[i];
        
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
    //$("#total").val(total);
    evaluar();
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
    calcularTotales();
    detalles = detalles-1;
    evaluar();
}

init();