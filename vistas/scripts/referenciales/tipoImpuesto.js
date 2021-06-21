var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

}

//funcion limpiar
function limpiar(){
    $("#idTipoImpuesto").val("");
    $("#descripcion").val("");
    $("#tipo").val("");
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
                    url: '../../ajax/referenciales/tipoImpuesto.php?op=listar',
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
        url: "../../ajax/referenciales/tipoImpuesto.php?op=guardaryeditar",
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

function mostrar(idTipoImpuesto){
    $.post("../../ajax/referenciales/tipoImpuesto.php?op=mostrar",{idTipoImpuesto : idTipoImpuesto}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idTipoImpuesto").val(data.idtipoimpuesto);
        $("#descripcion").val(data.descripcion);
        $("#tipo").val(data.tipo);

    });
}

//funciona para desactivar registros
function eliminar(idTipoImpuesto){
    bootbox.confirm("Estas Seguro de eliminar el Tipo de Impuesto?", function(result){
        if(result){
            $.post("../../ajax/referenciales/tipoImpuesto.php?op=eliminar", {idTipoImpuesto : idTipoImpuesto}, function(e){
                tabla.ajax.reload();
            });
                
        }
    })
}
// poner todo en mayuscula
function mayuscula(e){
   e.value = e.value.toUpperCase();
}

init();