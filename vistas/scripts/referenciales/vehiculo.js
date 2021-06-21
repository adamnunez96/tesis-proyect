var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

    //cargamos los items al select sucursal
    $.post("../../ajax/referenciales/vehiculo.php?op=selectMarca", function(r){
        $("#idmarca").html(r);
        $("#idmarca").selectpicker('refresh');
        $("#idmarca").val('4');
        $("#idmarca").selectpicker('refresh');
    });
}

//funcion limpiar
function limpiar(){
    $("#idvehiculo").val("");
    $("#modelo").val("");
    $("#chapa").val("");
    $("#observacion").val("");
    $("#anho").val("");
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
                    url: '../../ajax/referenciales/vehiculo.php?op=listar',
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
        url: "../../ajax/referenciales/vehiculo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos){
            bootbox.alert(datos); //aca recibimos el mensaje de mi deposito.php que esta en ajax
            mostrarform(false);
            tabla.ajax.reload();
        }

    });
    limpiar();

}

function mostrar(idvehiculo){
    $.post("../../ajax/referenciales/vehiculo.php?op=mostrar",{idvehiculo : idvehiculo}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#modelo").val(data.modelo);
        $("#idmarca").val(data.idmarca);
        $("#idmarca").selectpicker('refresh');
        $("#observacion").val(data.observacion);
        $("#chapa").val(data.chapa);
        $("#anho").val(data.anho);
        $("#idvehiculo").val(data.idvehiculo);
    });
}

//funciona para desactivar registros
function desactivar(idvehiculo){
    bootbox.confirm("Estas Seguro de Desactivar el Vehiculo?", function(result){
        if(result){
            $.post("../../ajax/referenciales/vehiculo.php?op=desactivar", {idvehiculo : idvehiculo}, function(e){
                tabla.ajax.reload();
            });
            
        }
    })
}

//funciona para activar registros
function activar(idvehiculo){
    bootbox.confirm("Estas Seguro de Activar el Vehiculo?", function(result){
        if(result){
            $.post("../../ajax/referenciales/vehiculo.php?op=activar", {idvehiculo : idvehiculo}, function(e){
                tabla.ajax.reload();
            });      
            
        }
    })
}

function mayuscula(e){

    e.value = e.value.toUpperCase();
    
}

init();