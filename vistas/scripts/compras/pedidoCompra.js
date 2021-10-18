var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
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
        listarMercaderias();
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles=0;
        $("#btnAgregarArt").show();
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
                    url: '../../ajax/compras/pedidoCompra.php?op=listar',
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

//funcion listar
function listarMercaderias(){

    tabla=$('#tblarticulos').dataTable({
        "aProcessing": true, //ativamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',//definimos los elementos del control de tabla
        buttons: [
        
        ],
        "ajax":
                {
                    url: '../../ajax/compras/pedidoCompra.php?op=listarMercaderias',
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
        url: "../../ajax/compras/pedidoCompra.php?op=guardaryeditar",
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

function mostrar(idpedido){

    $("#personal").val("");
    $("#sucursal").val("");
    $("#fecha_hora").val("");

    $.post("../../ajax/compras/pedidoCompra.php?op=mostrar",{idpedido : idpedido}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idpedido").val(data.idpedido);
        $("#personal").val(data.personal);
        $("#sucursal").val(data.sucursal);
        $("#fecha_hora").val(data.fecha);
        $("#obs").val(data.obs);

        //ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });
    $.post("../../ajax/compras/pedidoCompra.php/?op=listarDetalle&id="+idpedido, function(r){
        $("#detalles").html(r);
    });
}

//funciona para desactivar registros
function anular(idpedido){
    bootbox.confirm("Estas Seguro de anular el Pedido Compra?", function(result){
        if(result){
            $.post("../../ajax/compras/pedidoCompra.php?op=anular", {idpedido : idpedido}, function(e){
                tabla.ajax.reload();
            });    
        }
    });
}

//declaracion de variables necesarias para trabajar con las compras y sus detalles

// var impuesto=10;
var cont=0;
var detalles=0;
$("#guardar").hide();
$("#btnGuardar").hide();


function agregarDetalle(idmercaderia, descripcion){
    var cantidad=1;
    var detalleFinal = {};
    const objDetalle = {
        idmercaderia : idmercaderia,
        descripcion : descripcion
    }
    if(cont <= 0){
        detalleFinal.idmercaderia = objDetalle.idmercaderia;
        detalleFinal.descripcion = objDetalle.descripcion;
    }else{
        detalleFinal = validarDetalle(objDetalle);
    }
    

    if(detalleFinal != "" || detalleFinal != null){
        //var subtotal=cantidad*precio_compra;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idmercaderia" value="'+detalleFinal.idmercaderia+'">'+detalleFinal.idmercaderia+'</td>'+
        '<td><input type="hidden" name="descripcion"  value="'+detalleFinal.descripcion+'">'+detalleFinal.descripcion+'</td>'+
        '<td><input type="number" name="cantidad" id="cantidad" min="1" pattern="^[0-9]+" value="'+cantidad+'"></td>'+
        '</tr>';
        //detalleFinal.cantidad = document.getElementById("cantidad");
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        evaluar();
    }else{
        alert("error al ingresar el detalle, revisar los datos de la mercaderia");
    }
}


function validarDetalle(objDetalle){
    let detalles={
        idmercaderia: document.getElementsByName("idmercaderia"),
        descripcion: document.getElementsByName("descripcion"),
        cantidad : document.getElementById("cantidad")
    }
    let array = Array.from(detalles);
    const resultado = array.find((detalle)=>{
        if(+detalle.idmercaderia === +objDetalle.idmercaderia){
            return detalle;
        }
    })

    if(resultado){
        array = array.map((detalle)=>{
            if(+detalle.idmercaderia === +objDetalle.idmercaderia){
                return {
                    idmercaderia: detalle.idmercaderia,
                    descripcion: detalle.descripcion,
                    cantidad : +detalle.cantidad + 1
                };
            }
        });
    }else{
        return array.push(objDetalle);
    }

};

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
    evaluar();
}

init();