var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

    //cargamos los items al select ciudad
    $.post("../../ajax/referenciales/proveedor.php?op=selectCiudad", function(r){
        $("#idciudad").html(r);
        $("#idciudad").selectpicker('refresh');
        $("#idciudad").val('1');
        $("#idciudad").selectpicker('refresh');
    });
}

//funcion limpiar
function limpiar(){
    $("#idcliente").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#ci").val("");
    $("#telefono").val("");
    $("#direccion").val("");
    $("#correo").val("");
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
                    url: '../../ajax/referenciales/cliente.php?op=listar',
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
        url: "../../ajax/referenciales/cliente.php?op=guardaryeditar",
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

function mostrar(idcliente){
    $.post("../../ajax/referenciales/cliente.php?op=mostrar",{idcliente : idcliente}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#ci").val(data.ci);
        $("#idciudad").val(data.idciudad);
        $("#idciudad").selectpicker('refresh');
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#correo").val(data.correo);
        $("#idcliente").val(data.idcliente);
    });
}

//funciona para desactivar registros
function desactivar(idcliente){
    bootbox.confirm("Estas Seguro de Desactivar el Cliente?", function(result){
        if(result){
            $.post("../../ajax/referenciales/cliente.php?op=desactivar", {idcliente : idcliente}, function(e){
                tabla.ajax.reload();
            });
        }
    });
}

//funciona para activar registros
function activar(idcliente){
    bootbox.confirm("Estas Seguro de Activar el Proveedor?", function(result){
        if(result){
            $.post("../../ajax/referenciales/cliente.php?op=activar", {idcliente : idcliente}, function(e){
                tabla.ajax.reload();
            });      
        }
    });
}

function mayuscula(e){
    e.value = e.value.toUpperCase();
 }

init();