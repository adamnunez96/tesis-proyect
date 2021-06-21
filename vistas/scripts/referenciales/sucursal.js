var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

    //cargamos los items al select ciudad
    $.post("../../ajax/referenciales/sucursal.php?op=selectCiudad", function(r){
        $("#idciudad").html(r);
        $("#idciudad").selectpicker('refresh');
        $("#idciudad").val('1');
        $("#idciudad").selectpicker('refresh');
    });
}

//funcion limpiar
function limpiar(){
    $("#idsucursal").val("");
    $("#descripcion").val("");
    $("#direccion").val("");
    $("#telefono").val("");
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
                    url: '../../ajax/referenciales/sucursal.php?op=listar',
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
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../../ajax/referenciales/sucursal.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos){
            bootbox.alert(datos); //aca recibimos el mensaje de mi categoria.php que esta en ajax
            mostrarform(false);
            tabla.ajax.reload();
        }

    });
    limpiar();

}

function mostrar(idsucursal){
    $.post("../../ajax/referenciales/sucursal.php?op=mostrar",{idsucursal : idsucursal}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#descripcion").val(data.descripcion);
        $("#idciudad").val(data.idciudad);
        $("#idciudad").selectpicker('refresh');
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#idsucursal").val(data.idsucursal);
    });
}

//funciona para desactivar registros
function eliminar(idsucursal){
    bootbox.confirm("Estas eguro de eliminar la Sucursal?", function(result){
        if(result){
            $.post("../../ajax/referenciales/sucursal.php?op=eliminar", {idsucursal : idsucursal}, function(e){
                tabla.ajax.reload();
            });
                
        }
    })
}

function mayuscula(e){

    e.value = e.value.toUpperCase();
    
 }

init();