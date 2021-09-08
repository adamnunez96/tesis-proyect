var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    });

    //cargamos los items al select proveedor
    $.post("../../ajax/servicios/diagnostico.php?op=selectCliente", function(r){
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
    $("#idrecepcion").val("");
    $(".filas").remove();


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
        listarRecepciones();  
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles=0;
        $("#btnAgregarVehiculo").show();
        $("#btnAgregarRecepcion").show();
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnAgregarRecepcion").hide();
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
                    url: '../../ajax/servicios/diagnostico.php?op=listar',
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
                    url: '../../ajax/servicios/diagnostico.php?op=listarVehiculos',
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

function listarRecepciones(){

    tabla=$('#tblrecepciones').dataTable({
        "aProcessing": true, //ativamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',//definimos los elementos del control de tabla
        buttons: [
        
        ],
        "ajax":
                {
                    url: '../../ajax/servicios/diagnostico.php?op=listarRecepciones',
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
        url: "../../ajax/servicios/diagnostico.php?op=guardaryeditar",
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

function mostrar(iddiagnostico){

    $("#idpersonal").val("");
    $("#idsucursal").val("");
    $("#fecha_hora").val("");

    $.post("../../ajax/servicios/diagnostico.php?op=mostrar",{iddiagnostico : iddiagnostico}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#iddiagnostico").val(data.iddiagnostico);
        $("#idrecepcion").val(data.idrecepcion);
        $("#idcliente").val(data.idcliente);
        $("#idcliente").selectpicker('refresh');
        $("#idpersonal").val(data.personal);
        $("#idsucursal").val(data.sucursal);
        $("#fecha_hora").val(data.fecha);
        $("#obs").val(data.descripcion);
        $("#motivo").val(data.motivo);
        $("#motivo").selectpicker('refresh');

        //ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarVehiculo").hide();
    });
    $.post("../../ajax/servicios/diagnostico.php/?op=listarDetalle&id="+iddiagnostico, function(r){
        $("#detalles").html(r);
    });

}

function agregarRecepcion(idrecepcion){

    $.post("../../ajax/servicios/diagnostico.php?op=listarCabeceraRecepcion",{idrecepcion : idrecepcion}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idrecepcion").val(data.idrecepcion);

        //ocultar y mostrar los botones
        $("#btnCancelar").show();

    });
    $.post("../../ajax/servicios/diagnostico.php/?op=listarDetalleRecepcion&id="+idrecepcion, function(res){
        let data = JSON.parse(res);
        mostrarDetalle(data);
    });
}

//declaracion de variables necesarias para trabajar con las compras y sus detalles

var cont=0;
var detalles=0;
$("#guardar").hide();
$("#btnGuardar").hide();

//funciona para desactivar registros
function anular(iddiagnostico){
    bootbox.confirm("Estas Seguro de anular la Orden de Compra?", function(result){
        if(result){
            $.post("../../ajax/servicios/diagnostico.php?op=anular", {iddiagnostico : iddiagnostico}, function(e){
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
        console.log(fila);
        $('#detalles').append(fila);
        evaluar();
    });  
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
    detalles = detalles-1;
    console.log(detalles);
    evaluar();
}

init();