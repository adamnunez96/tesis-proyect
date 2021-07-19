var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    });

    //cargamos los items al select del personal que enviar
    $.post("../../ajax/compras/notaRemision.php?op=selectPersonalEnvia", function(r){
        $("#idperEnvia").html(r);
        $("#idperEnvia").selectpicker('refresh');
        $("#idperEnvia").val('1');
        $("#idperEnvia").selectpicker('refresh');
    });

    //cargamos los items al select deposito
    $.post("../../ajax/compras/notaRemision.php?op=selectDeposito", function(r){
        $("#iddeposito").html(r);
        $("#iddeposito").selectpicker('refresh');
        $("#iddeposito").val('1');
        $("#iddeposito").selectpicker('refresh');
    });

}

//funcion limpiar
function limpiar(){

    $("#obs").val("");
    $("#fecha_hora").val("");
    
    $(".filas").remove();
    $("#total").html("0");

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
        $("#btnAgregarPedido").show();
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnAgregarPedido").hide();
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
                    url: '../../ajax/compras/notaRemision.php?op=listar',
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
                    url: '../../ajax/compras/notaRemision.php?op=listarMercaderias',
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
        url: "../../ajax/compras/notaRemision.php?op=guardaryeditar",
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

function mostrar(idnota){

    $("#idpersonal").val("");
    $("#idsucursal").val("");
    $("#fecha_hora").val("");

    $.post("../../ajax/compras/notaRemision.php?op=mostrar",{idnota : idnota}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);
        $("#idnota").val(data.idnotaremision);
        $("#iddeposito").val(data.iddeposito);
        $("#iddeposito").selectpicker('refresh');
        $("#idperEnvia").val(data.idpersonalenvia);
        $("#idperEnvia").selectpicker('refresh');
        $("#idpersonal").val(data.perrecibe);
        $("#idpersonal").selectpicker('refresh');
        $("#idsucursal").val(data.sucursal);
        $("#fecha_hora").val(data.fecha);

        //ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });
    $.post("../../ajax/compras/notaRemision.php/?op=listarDetalle&id="+idnota, function(r){
        $("#detalles").html(r);
    });
}

//funciona para desactivar registros
function anular(idnota){
    bootbox.confirm("Estas Seguro de anular la Nota de Remision?", function(result){
        if(result){
            $.post("../../ajax/compras/notaRemision.php?op=anular", {idnota : idnota}, function(e){
                tabla.ajax.reload();
            });    
        }
    });
}


//declaracion de variables necesarias para trabajar con las compras y sus detalles
var cont=0;
var detalles=0;
$("#guardar").hide();
$("#btnGuardar").hide();


function agregarDetalle(idmercaderia, descripcion, precio){
    var cantidad=1;

    if(idmercaderia != ""){
        var subtotal=cantidad*precio;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idmercaderia[]" value="'+idmercaderia+'">'+idmercaderia+'</td>'+
        '<td><input type="hidden" name="descripcion[]" value="'+descripcion+'">'+descripcion+'</td>'+
        '<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input type="hidden" name="precio[]" id="precio[]" value="'+precio+'">'+precio+'</td>'+
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

function modificarSubtotales(){
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio[]");
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
    $("#total_compra").val(total);
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
    calcularTotales()
    detalles = detalles-1;
    console.log(detalles);
    evaluar();
}

init();