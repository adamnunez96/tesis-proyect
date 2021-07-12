var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    });

    //cargamos los items al select deposito
    $.post("../../ajax/compras/ajusteStock.php?op=selectDeposito", function(r){
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

    let tipo = document.getElementById("tipo_ajuste");
    tipo[0].disabled = false;
    tipo[1].disabled = false;
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
                    url: '../../ajax/compras/ajusteStock.php?op=listar',
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
                    url: '../../ajax/compras/ajusteStock.php?op=listarMercaderias',
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
        url: "../../ajax/compras/ajusteStock.php?op=guardaryeditar",
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


function mostrar(idajuste){

    $("#idpersonal").val("");
    $("#idsucursal").val("");
    $("#fecha_hora").val("");

    $.post("../../ajax/compras/ajusteStock.php?op=mostrar",{idajuste : idajuste}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idajuste").val(data.idajuste);
        $("#idsucursal").val(data.sucursal);
        $("#iddeposito").val(data.iddeposito);
        $("#iddeposito").selectpicker('refresh');
        $("#idpersonal").val(data.personal);
        
        $("#fecha_hora").val(data.fecha);
        $("#obs").val(data.obs);
        $("#tipo_ajuste").val(data.tipoajuste);
        $("#tipo_ajuste").selectpicker('refresh');
        $("#montoTotal").val(data.monto);

        //ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
        $("#btnAgregarPedido").hide();
    });
    $.post("../../ajax/compras/ajusteStock.php/?op=listarDetalle&id="+idajuste, function(r){
        $("#detalles").html(r);
    });
}

function modificar(idajuste){
    $("#idpersonal").val("");
    $("#idsucursal").val("");
    $("#fecha_hora").val("");
    let tipo = document.getElementById("tipo_ajuste");

    $.post("../../ajax/compras/ajusteStock.php?op=mostrar",{idajuste : idajuste}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idajuste").val(data.idajuste);
        $("#idsucursal").val(data.sucursal);
        $("#iddeposito").val(data.iddeposito)
        $("#iddeposito").val(data.iddeposito);
        $("#iddeposito").selectpicker('refresh');
        $("#idpersonal").val(data.personal);
        
       // $("#fecha_hora").val(data.fecha);
        $("#obs").val(data.obs);
        if(data.tipoajuste == 1){
            tipo[1].disabled = true;
            $("#tipo_ajuste").val(data.tipoajuste)
            $("#tipo_ajuste").selectpicker('refresh');
        }else{
            tipo[0].disabled = true;
            $("#tipo_ajuste").val(data.tipoajuste)
            $("#tipo_ajuste").selectpicker('refresh');
        }
        $("#montoTotal").val(data.monto);

        //ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();

    });
    $.post("../../ajax/compras/ajusteStock.php/?op=mostrarDetalle&id="+idajuste, function(res){
        let data = JSON.parse(res);
        mostrarDetalle(data);
    });
}

//funciona para desactivar registros
function anular(idajuste, tipoajuste){
    bootbox.confirm("Estas Seguro de anular El ajuste de Compra?", function(result){
        if(result){
            $.post("../../ajax/compras/ajusteStock.php?op=anular", {idajuste : idajuste, tipo_ajuste : tipoajuste}, function(e){
                tabla.ajax.reload();
                bootbox.alert(e);
                console.log(e);
            });    
        }
    });
}

//declaracion de variables necesarias para trabajar con las compras y sus detalles

var cont=0;
var detalles=0;
$("#guardar").hide();
$("#btnGuardar").hide();

function agregarDetalle(idmercaderia, descripcion, preciocompra){
    var cantidad=1;

    if(idmercaderia != ""){
        var subtotal=cantidad*preciocompra;
        let fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idmercaderia[]" value="'+idmercaderia+'">'+idmercaderia+'</td>'+
        '<td><input type="hidden" name="descripcion[]" value="'+descripcion+'">'+descripcion+'</td>'+
        '<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input type="hidden" name="preciocompra[]" id="preciocompra[]" value="'+preciocompra+'">'+preciocompra+'</td>'+
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
        let subtotal=detalle[3]*detalle[4];
        let fila = '<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idmercaderia[]" value="'+detalle[1]+'">'+detalle[1]+'</td>'+
        '<td><input type="hidden" name="descripcion[]" value="'+detalle[2]+'">'+detalle[2]+'</td>'+
        '<td><input type="number" name="cantidad[]" style="width:80px" value="'+detalle[3]+'"></td>'+
        '<td><input type="hidden" name="preciocompra[]" value="'+detalle[4]+'">'+detalle[4]+'</td>'+
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
    evaluar();
}

init();