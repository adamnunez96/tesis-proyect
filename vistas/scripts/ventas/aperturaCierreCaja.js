var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

    //cargamos los items al select deposito
    $.post("../../ajax/ventas/aperturaCierreCaja.php?op=selectCaja", function(r){
        $("#idcaja").html(r);
        $("#idcaja").selectpicker('refresh');
        $("#idcaja").val(1);
        $("#idcaja").selectpicker('refresh');
    });

}

//funcion limpiar
function limpiar(){
    $("#idcaja").val("");
    $("#montoApertura").val("0");
    $("#fecha_hora").val("");

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
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
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
            'excelHtml5',
            'pdf'
        ],
        "ajax":
                {
                    url: '../../ajax/ventas/aperturaCierreCaja.php?op=listar',
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

// funcion para guardar o editar

function guardaryeditar(e){
    e.preventDefault(); //no se activara la accion predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../../ajax/ventas/aperturaCierreCaja.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos){
            bootbox.alert(datos); //aca recibimos el mensaje de mi ciudad.php que esta en ajax
            mostrarform(false);
            tabla.ajax.reload();
        }

    });
    limpiar();

}

function mostrar(idcaja){
    $.post("../../ajax/referenciales/caja.php?op=mostrar",{idcaja : idcaja}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcaja").val(data.idcaja);
        $("#descripcion").val(data.descripcion);

    });
}

//funciona para desactivar registros
function cierre(idapertura){
    bootbox.confirm("Estas Seguro de Cerrar la Caja?", function(result){
        if(result){
            $.post("../../ajax/ventas/aperturaCierreCaja.php?op=cierre", {idapertura : idapertura}, function(e){
                console.log(e);
                tabla.ajax.reload();
            });
                
        }
    })
}

function valideKey(evt){
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if((code==8) || (code>=48 && code<=57)) { // backspace.
      return true;
    } else{ // other keys.
      return false;
    }
}
// poner todo en mayuscula
/*function mayuscula(){
   let descripcion = document.querySelector('#descripcion');
   descripcion.value = descripcion.value.toUpperCase();
}*/

init();