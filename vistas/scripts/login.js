function init(){
    //Cargamos los items al select categoria
    $.post("../ajax/referenciales/sucursal.php?op=selectSucursal", function(r){
        $("#sucursal").html(r);
        $("#sucursal").selectpicker('refresh');
        $("#sucursal").val('1');
        $("#sucursal").selectpicker('refresh');
    });

}


$("#frmAcceso").on('submit', function(e){
    e.preventDefault();
    
    logina=$("#logina").val();
    clavea=$("#clavea").val();
    sucursal=$("#sucursal").val();

    validarAcceso(logina, clavea, sucursal);
   
})

var intentos = 0;
var accedio = null;
function validarAcceso(logina, clavea, sucursal){

    $.post("../ajax/referenciales/usuario.php?op=verificar",
    {"logina":logina, "clavea":clavea, "sucursal":sucursal},function(data){   
        
            if(intentos < 3){
                accedio = "Si";
                if(data != "null"){
                    intentos++;
                    $.post("../ajax/referenciales/usuario.php?op=auditoria",{"logina":logina, "intentos":intentos, "accedio":accedio});
                    $(location).attr("href", "escritorio.php");
                    console.log(intentos);
                    intentos=0;
                }else{
                    intentos++;
                    accedio = "No";
                    bootbox.alert("Usuario y/o Password incorrectos");
                    $.post("../ajax/referenciales/usuario.php?op=auditoria",{"logina":logina, "intentos":intentos, "accedio": accedio});
                    $("#logina").val("");
                    $("#clavea").val("");
                }
            }else{
                console.log(intentos + " - " + logina);
                accedio = "No";
                $.post("../ajax/referenciales/usuario.php?op=inactivar",{"logina":logina},function(r){
                    bootbox.alert(r);
                });
                $.post("../ajax/referenciales/usuario.php?op=auditoria",{"logina":logina, "intentos":intentos, "accedio": accedio});
                intentos=0;
            }
          
    });
}

init();

  //console.log(data);
            // if(data == "null"){

            //     bootbox.alert("Usuario y/o Password incorrectos");
            //     $("#logina").val("");
            //     $("#clavea").val("");
            // }else{
            //     $(location).attr("href", "escritorio.php");
            // } 
