/**************************************Inicio de sesion*****************************************/
    var formulario = document.querySelector("#formulario"), //Tomo al formulario
    	mensajesError = jQuery(".clsError");
   		patronCorreo = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i, //Expresión regular de validación del campo Correo
       	patronAlfabetico = /^([a-zA-Z]+[\s]*)+$/, //Campos: Nombre, Ocupación
       	patronContrasena = /^([a-zA-Z0-9]{6,20})$/;//Campo: Contraseña

    formulario.addEventListener("submit", function(event){ //Cuando se intente enviar los datos
        event.preventDefault(); //Cancelo el envío

     	var contrasena = "",
       		campoCorrecto = true, //true = esta correcto el campo, false = tiene error
       		enviar = true;

     	for (var i = 0; i<formulario.length && formulario[i].tagName == "INPUT" ; i++) {
     		if(formulario[i].value == ""){//Error si está vacío el campo
     			campoCorrecto = false;
     			jQuery(mensajesError[i]).css('display','inline');
     			jQuery(mensajesError[i]).text("Favor de ingresar "+formulario[i].id.replace("contrasena","contraseña").replace("rptContrasena","contraseña"));
     		}else{
     			if(formulario[i].id == "correo"){//Caso especial para campo correo
     				if(!patronCorreo.test(formulario[i].value)){
     					campoCorrecto = false;
     					jQuery(mensajesError[i]).css('display','inline');
	     				jQuery(mensajesError[i]).text("El correo es incorrecto");
     				}else{
     					jQuery(mensajesError[i]).css('display','none');
     				}
     			}else if(formulario[i].id == "contrasena" || formulario[i].id == "rptContrasena"){//Caso especial para campo contaseña
     				if(!patronContrasena.test(formulario[i].value)){
     					campoCorrecto = false;
     					jQuery(mensajesError[i]).css('display','inline');
	     				jQuery(mensajesError[i]).text("Contraseña no válida")
     				}else{
     					if(formulario[i].id == "rptContrasena"){
     						if(contrasena == formulario[i].value){
     							campoCorrecto = true;
     							jQuery(mensajesError[i]).css('display','none');
     						}else{
     							campoCorrecto = false;
     							jQuery(mensajesError[i]).css('display','inline');
	     						jQuery(mensajesError[i]).text("Las contraseñas no coinciden");
     						}
     					}else{
     						contrasena = formulario[i].value;
     						jQuery(mensajesError[i]).css('display','none');
     					}
     				}
     			}else if(formulario[i].id == "nombre" || formulario[i].id == "ocupacion"){//Caso para campos de solo letras
     				if(!patronAlfabetico.test(formulario[i].value)){
     					campoCorrecto = false;
     					jQuery(mensajesError[i]).css('display','inline');
	     				jQuery(mensajesError[i]).text("Contiene caracteres inválidos");
     				}else{
     					jQuery(mensajesError[i]).css('display','none');
     				}
     			}else{//Los demás campos que no requieren validación

     			}
     		}

     		if(!campoCorrecto){
     			jQuery(formulario[i]).addClass("campoError");
     			campoCorrecto = true;
     			enviar = false;
     		}else{
     			if(jQuery(formulario[i]).hasClass("campoError")){
     				jQuery(formulario[i]).removeClass("campoError");
     			}
     		}
     	}

     	if(enviar == true){
     		formulario.submit();
     	}

    }, false);
