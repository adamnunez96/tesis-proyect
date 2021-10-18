var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

    //mostramos los permisos
    $.post("../../ajax/referenciales/usuario.php?op=permisos&id=",function(r){
        $("#permisos").html(r);
    });

}

//funcion limpiar
function limpiar(){
    
    $("#usuario").val("");
    $("#clave").val("");
    $("#idusuario").val("");

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

    $.post("../../ajax/referenciales/usuario.php?op=permisos&id=",function(r){
        $("#permisos").html(r);
    });

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
                    url: '../../ajax/referenciales/usuario.php?op=listar',
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
        url: "../../ajax/referenciales/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos){
            bootbox.alert(datos); //aca recibimos el mensaje de mi usuario.php que esta en ajax
            mostrarform(false);
            tabla.ajax.reload();
        }

    });
    limpiar();

    //linea agregada actualizar los estados de los checks
    $.post("../../ajax/referenciales/usuario.php?op=permisos&id=",function(r){
        $("#permisos").html(r);
    });

}

function mostrar(idusuario){
    $.post("../../ajax/referenciales/usuario.php?op=mostrar",{idusuario : idusuario}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#idusuario").val(data.idusuario);
        $("#usuario").val(data.usuario);
        $("#clave").val("");
 
    });

    $.post("../../ajax/referenciales/usuario.php?op=permisos&id="+idusuario,function(r){
        $("#permisos").html(r);
    });

}

//funcion para desactivar registros
function desactivar(idusuario){
    bootbox.confirm("Esta Seguro de desactivar el Usuario?", function(result){
        if(result){
            $.post("../../ajax/referenciales/usuario.php?op=desactivar", {idusuario : idusuario}, function(e){
                tabla.ajax.reload();
            });    
        }
    })
}

//funciona para activar registros
function activar(idusuario){
    bootbox.confirm("Esta Seguro de activar el Usuario?", function(result){
        if(result){
            $.post("../../ajax/referenciales/usuario.php?op=activar", {idusuario : idusuario}, function(e){
                tabla.ajax.reload();
            });
                
            
        }
    })
}

init();