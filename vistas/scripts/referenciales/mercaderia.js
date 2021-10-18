var tabla;

//funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
  
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

    //cargamos los items al select marcas
    $.post("../../ajax/referenciales/mercaderia.php?op=selectMarca", function(r){
        $("#idmarca").html(r);
        $("#idmarca").selectpicker('refresh');
        $("#idmarca").val('2');
        $("#idmarca").selectpicker('refresh');
    });

    //cargamos los items al select tipo Impuesto
    $.post("../../ajax/referenciales/mercaderia.php?op=selectTipoImpuesto", function(r){
        $("#idtipoimpuesto").html(r);
        $("#idtipoimpuesto").selectpicker('refresh');
        $("#idtipoimpuesto").val('1');
        $("#idtipoimpuesto").selectpicker('refresh');
    });

    $("#imagenmuestra").hide();
}

//funcion limpiar
function limpiar(){
    $("#idmercaderia").val("");
    $("#descripcion").val("");
    $("#precioCompra").val("");
    $("#precioVenta").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
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
                    url: '../../ajax/referenciales/mercaderia.php?op=listar',
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
    var formData = new FormData($("#formulario")[0]);
    let precioCompra = +$('#precioCompra').val();
    let precioVenta = +$('#precioVenta').val();
    if(precioCompra != 0 && precioVenta != 0){
        if(precioCompra >= precioVenta){
            bootbox.alert('El precio de Venta debe ser Mayor al precio de Compra. Favor Verificar');
            precioCompra = $('#precioVenta').val("");

        }else{
            $("#btnGuardar").prop("disabled",true);
            $.ajax({
                url: "../../ajax/referenciales/mercaderia.php?op=guardaryeditar",
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
        }  
    }else{
        bootbox.alert('Los montos ingresados deben ser mayor a 0. Favor verificar');

    }    
    limpiar();
}

function mostrar(idmercaderia){
    $.post("../../ajax/referenciales/mercaderia.php?op=mostrar",{idmercaderia : idmercaderia}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);

        $("#descripcion").val(data.descripcion);
        $("#precioCompra").val(data.preciocompra);
        $("#precioVenta").val(data.precioventa);
        $("#idmarca").val(data.idmarca);
        $("#idmarca").selectpicker('refresh');
        $("#idtipoimpuesto").val(data.idtipoimpuesto);
        $("#idtipoimpuesto").selectpicker('refresh');
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../../files/mercaderias/"+data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#idmercaderia").val(data.idmercaderia);
    });
}

//funciona para desactivar registros
function desactivar(idmercaderia){
    bootbox.confirm("Estas Seguro de Desactivar la Mercaderia?", function(result){
        if(result){
            $.post("../../ajax/referenciales/mercaderia.php?op=desactivar", {idmercaderia : idmercaderia}, function(e){
                tabla.ajax.reload();
            });
            
        }
    })
}

//funciona para activar registros
function activar(idmercaderia){
    bootbox.confirm("Estas Seguro de Activar el Mercaderia?", function(result){
        if(result){
            $.post("../../ajax/referenciales/mercaderia.php?op=activar", {idmercaderia : idmercaderia}, function(e){
                tabla.ajax.reload();
            });      
            
        }
    })
}

function mayuscula(e){
    e.value = e.value.toUpperCase();
 }

//  $("#precioVenta").on("blur",()=>{
//     validarPrecio(); //validamos que el precion
//  });

 /*function validarPrecio(){
    let precioCompra = +$('#precioCompra').val();
    let precioVenta = +$('#precioVenta').val();

    if(precioCompra >= precioVenta){
        bootbox.alert('El precio de Venta debe ser MAYOR al precio de Compra!');
        precioCompra = $('#precioVenta').val("");
    };
 }*/


 function valideKey(evt){
    
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if((code==8) || (code>=48 && code<=57)) { // backspace.
      return true;
    } else{ // other keys.
      return false;
    }
}

init();