var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    });

    //cargamos los items al select proveedor
    $.post("../../ajax/servicios/recepcion.php?op=selectCliente", function(r){
        $("#idcliente").html(r);
        $("#idcliente").selectpicker('refresh');
        $("#idcliente").val('1');
        $("#idcliente").selectpicker('refresh');
    });

}

//funcion limpiar
function limpiar(){
    // $("#personal").val("");
    // $("#sucursal").val("");
    $("#obs").val("");
    $("#fecha_hora").val("");
    
    $(".filas").remove();
    //$("#total").html("0");

    //obtenemos la fecha y hora actual
    var now = new Date();
    var day = ("0" + (now.getDate())).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var year = now.getFullYear();
    var hour = ("0" + (now.getHours())).slice(-2);
    var minute = ("0" + (now.getMinutes())).slice(-2);
    var second = ("0" + (now.getSeconds())).slice(-2);
    var nowHour = `${hour}:${minute}:${second}`;
    // var today = now.getFullYear()+"-"+(month)+"-"+(day)+"T"+nowHour;
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
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles=0;
        $("#btnAgregarVehiculo").show();
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
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
                    url: '../../ajax/servicios/recepcion.php?op=listar',
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
                    url: '../../ajax/servicios/recepcion.php?op=listarVehiculos',
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
        url: "../../ajax/servicios/recepcion.php?op=guardaryeditar",
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

function mostrar(idrecepcion){

    $("#idpersonal").val("");
    $("#idsucursal").val("");
    $("#fecha_hora").val("");

    $.post("../../ajax/servicios/recepcion.php?op=mostrar",{idrecepcion : idrecepcion}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

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

        let detalle = mostrarDetalle(data.idvehiculo, data.vehiculo ,data.chapa);
        $("#detalles").html(detalle);

    });

}

// window.onload = ()=>{
//     btnModificar = document.querySelector("#btnModificar");
//     btnModificar.addEventListener("click",()=>{
//     alert("hola");
// });
// };


function modificar(idrecepcion){

    $("#personal").val("");
    $("#sucursal").val("");
    $("#fecha_hora").val("");

    $.post("../../ajax/servicios/recepcion.php?op=mostrar",{idrecepcion : idrecepcion}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idrecepcion").val(data.idrecepcion);
        $("#idcliente").val(data.idcliente);
        $("#idcliente").selectpicker('refresh');
        $("#idpersonal").val(data.personal);
        $("#idsucursal").val(data.sucursal);
        //let fecha = document.querySelector("#fecha_hora");
        $("#fecha_hora").attr({"min": data.fecha, "max":data.fecha});
        $("#fecha_hora").val(data.fecha);

        $("#obs").val(data.descripcion);
        $("#motivo").val(data.motivo);
        $("#motivo").selectpicker('refresh');
        //ocultar y mostrar los botones
//        $("#btnGuardar").hide();
//        $("#btnCancelar").show();
//        $("#btnAgregarVehiculo").hide();

        editarDetalle(data.idvehiculo, data.vehiculo,data.chapa);   

    });

}

function mostrarDetalle(idvehiculo, vehiculo, chapa){
    let detalle = "";
    detalle = `<thead style="background-color:#A9D0F5">
                <th style="width:30px">Opciones</th>
                <th>Id</th>
                <th>Vehiculo</th>
                <th>Chapa</th>
            </thead>

        <tr class="filas"><td></td><td>${idvehiculo}</td><td>${vehiculo}</td><td>${chapa}</td></tr>`
    
        return detalle;
}

//declaracion de variables necesarias para trabajar con las compras y sus detalles

// var impuesto=10;
var cont=0;
var detalles=0;
$("#guardar").hide();
$("#btnGuardar").hide();

function editarDetalle(idvehiculo, vehiculo, chapa){
    let detalle = "";
    detalle += `<tr class="filas" id="fila${cont}">
                    <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
                    <td><input type="hidden" name="idvehiculo" value="${idvehiculo}">${idvehiculo}</td>
                    <td><input type="hidden" name="vehiculo" value="${vehiculo}">${vehiculo}</td>
                    <td><input type="hidden" name="chapa" value="${chapa}">${chapa}</td>
                </tr>`;

        cont++;
        detalles=detalles+1;
        $("#detalles").append(detalle);
        evaluar();
}

//funciona para desactivar registros
function anular(idrecepcion){
    bootbox.confirm("Estas Seguro de anular la Orden de Compra?", function(result){
        if(result){
            $.post("../../ajax/servicios/recepcion.php?op=anular", {idrecepcion : idrecepcion}, function(e){
                tabla.ajax.reload();
            });    
        }
    });
}

function agregarVehiculo(idvehiculo, vehiculo, chapa){

    if(idvehiculo != ""){
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idvehiculo" value="'+idvehiculo+'">'+idvehiculo+'</td>'+
        '<td><input type="hidden" name="vehiculo" value="'+vehiculo+'">'+vehiculo+'</td>'+
        '<td><input type="hidden" name="chapa" id="chapa" value="'+chapa+'">'+chapa+'</td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        evaluar();
    }else{
        alert("error al ingresar el detalle, revisar los datos de la mercaderia");
    }
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