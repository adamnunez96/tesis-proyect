
//funcion que se ejecuta al inicio
function init(){
    listar();
    $("#fecha_inicio").change(listar);
    $("#fecha_fin").change(listar);
}

var tabla;
//funcion listar
function listar(){

    let fecha_inicio = $("#fecha_inicio").val();
    let fecha_fin = $("#fecha_fin").val();

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
                    url: '../../ajax/compras/libroCompra.php?op=listar',
                    data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
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

init();