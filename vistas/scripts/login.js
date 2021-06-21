function init(){
    //Cargamos los items al select categoria
    $.post("../ajax/referenciales/sucursal.php?op=selectSucursal", function(r){
        $("#sucursal").html(r);
        $("#sucursal").selectpicker('refresh');
        $("#sucursal").val('1');
        $("#sucursal").selectpicker('refresh');
    });
    var ubi =window.location.href;
    console.log(ubi);
}

let intentos = 1;

$("#frmAcceso").on('submit', function(e){
    e.preventDefault();
    
    logina=$("#logina").val();
    clavea=$("#clavea").val();
    sucursal=$("#sucursal").val();

   
    $.post("../ajax/referenciales/usuario.php?op=verificar",
    {"logina":logina, "clavea":clavea, "sucursal":sucursal},function(data){   
        
            if(intentos <= 3){

                if(data != "null"){
                    $(location).attr("href", "escritorio.php");
                    console.log(data);
                    intentos=1;
                }else{
                    intentos++;
                    console.log(intentos);
                    console.log(data);
                    bootbox.alert("Usuario y/o Password incorrectos");
                    $("#logina").val("");
                    $("#clavea").val("");
                }
            }else{
 
                $.post("../ajax/referenciales/usuario.php?op=inactivar",{"logina":logina},function(r){
                    bootbox.alert(r);
                });
                intentos=1;
            }
          
    });
})

init();

  //console.log(data);
            // if(data == "null"){

            //     bootbox.alert("Usuario y/o Password incorrectos");
            //     $("#logina").val("");
            //     $("#clavea").val("");
            // }else{
            //     $(location).attr("href", "escritorio.php");
            // } 
