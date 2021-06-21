var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

    //cargamos los items al select ciudad
    $.post("../../ajax/referenciales/personal.php?op=selectCiudad", function(r){
        $("#idciudad").html(r);
        $("#idciudad").selectpicker('refresh');
        $("#idciudad").val('1');
        $("#idciudad").selectpicker('refresh');
    });

    //cargamos los items al select usuario
    $.post("../../ajax/referenciales/personal.php?op=selectUsuario", function(r){
        $("#idusuario").html(r);
        $("#idusuario").selectpicker('refresh');
        $("#idusuario").val('1');
        $("#idusuario").selectpicker('refresh');
    });

    //cargamos los items al select sucursal
    $.post("../../ajax/referenciales/personal.php?op=selectSucursal", function(r){
        $("#idsucursal").html(r);
        $("#idsucursal").selectpicker('refresh');
        $("#idsucursal").val('1');
        $("#idsucursal").selectpicker('refresh');
    });

    $("#imagenmuestra").hide();
}

//funcion limpiar
function limpiar(){
    $("#idpersonal").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#documento").val("");
    $("#telefono").val("");
    $("#direccion").val("");
    $("#correo").val("");
    $("#cargo").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").show();
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
                    url: '../../ajax/referenciales/personal.php?op=listar',
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
        url: "../../ajax/referenciales/personal.php?op=guardaryeditar",
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

function mostrar(idpersonal){
    $.post("../../ajax/referenciales/personal.php?op=mostrar",{idpersonal : idpersonal}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idpersonal").val(data.idpersonal);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#documento").val(data.documento);
        $("#idsucursal").val(data.idsucursal);
        $("#idsucursal").selectpicker('refresh');
        $("#idciudad").val(data.idciudad);
        $("#idciudad").selectpicker('refresh');
        $("#idusuario").val(data.idusuario);
        $("#idusuario").selectpicker('refresh');
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#correo").val(data.correo);
        $("#cargo").val(data.cargo);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../../files/usuarios/"+data.imagen);
        $("#imagenactual").val(data.imagen);
    });
}

function mostrar2(idpersonal){ //este mostrar dos usamos para el boton mostrar los datos ya que esconde el boton guardar
    $.post("../../ajax/referenciales/personal.php?op=mostrar",{idpersonal : idpersonal}, function(data, status){
        data = JSON.parse(data);
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        $("#btnGuardar").hide();
        $("#btnCancelar").show();

        $("#idpersonal").val(data.idpersonal);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#documento").val(data.documento);
        $("#idsucursal").val(data.idsucursal);
        $("#idsucursal").selectpicker('refresh');
        $("#idciudad").val(data.idciudad);
        $("#idciudad").selectpicker('refresh');
        $("#idusuario").val(data.idusuario);
        $("#idusuario").selectpicker('refresh');
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#correo").val(data.correo);
        $("#cargo").val(data.cargo);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../../files/usuarios/"+data.imagen);
        $("#imagenactual").val(data.imagen);
        
    });
}

//funciona para desactivar registros
function desactivar(idpersonal){
    bootbox.confirm("Estas Seguro de Desactivar el Personal?", function(result){
        if(result){
            $.post("../../ajax/referenciales/personal.php?op=desactivar", {idpersonal : idpersonal}, function(e){
                tabla.ajax.reload();
            });
        }
    });
}

//funciona para activar registros
function activar(idpersonal){
    bootbox.confirm("Estas Seguro de Activar el Personal?", function(result){
        if(result){
            $.post("../../ajax/referenciales/personal.php?op=activar", {idpersonal : idpersonal}, function(e){
                tabla.ajax.reload();
            });      
        }
    });
}

function mayuscula(e){
    e.value = e.value.toUpperCase();
 }

init();