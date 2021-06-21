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
    $("#idcaja").val("");
    $("#descripcion").val("");
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
                    url: '../../ajax/referenciales/caja.php?op=listar',
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
        url: "../../ajax/referenciales/caja.php?op=guardaryeditar",
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
function eliminar(idcaja){
    bootbox.confirm("Estas Seguro de eliminar la Caja?", function(result){
        if(result){
            $.post("../../ajax/referenciales/caja.php?op=eliminar", {idcaja : idcaja}, function(e){
                tabla.ajax.reload();
            });
                
        }
    })
}
// poner todo en mayuscula
function mayuscula(){
   let descripcion = document.querySelector('#descripcion');
   descripcion.value = descripcion.value.toUpperCase();
}

init();