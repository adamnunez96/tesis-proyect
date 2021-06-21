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
    $("#identidademisora").val("");
    $("#descripcion").val("");
    $("#telefono").val("");
    $("#direccion").val("");
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
                    url: '../../ajax/referenciales/entidadEmisora.php?op=listar',
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
        url: "../../ajax/referenciales/entidadEmisora.php?op=guardaryeditar",
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

function mostrar(identidademisora){
    $.post("../../ajax/referenciales/entidadEmisora.php?op=mostrar",{identidademisora : identidademisora}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#identidademisora").val(data.identidademisora);
        $("#descripcion").val(data.descripcion);
        $("#telefono").val(data.telefono);
        $("#direccion").val(data.direccion);

    });
}

//funciona para eliminar registros
function eliminar(identidademisora){
    bootbox.confirm("Estas Seguro de eliminar la Entidad Emisora?", function(result){
        if(result){
            $.post("../../ajax/referenciales/entidadEmisora.php?op=eliminar", {identidademisora : identidademisora}, function(e){
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