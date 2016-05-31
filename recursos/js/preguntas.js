$(document).ready(function() {
    /*$("#numok").click(function() {
        var numero = $("#numPregunta").val();
        for(var cont = numero; cont > 0; cont--){
            $('#preguntas').clone().attr('id','preguntas'+cont).insertAfter('#preguntas');
            //$('#pregunta').attr('id','pregunta'+cont);
            $('#labelPregunta').text('Pregunta '+cont);
            $('#pregunta').attr({
                name:"pregunta"+cont
            });
            $('#respuesta1').attr({
                name:"respuesta1"+cont
            });
            $('#respuesta2').attr({
                name:"respuesta2"+cont
            });
            $('#respuesta3').attr({
                name:"respuesta3"+cont
            });
            $('#respuesta4').attr({
                name:"respuesta4"+cont
            });
            if($('#opcion1').length){
                //alert('aqui2');
                $('#opcion1').attr({
                    name:"opcion"+cont
                });
                $('#opcion2').attr({
                    name:"opcion"+cont
                });
                $('#opcion3').attr({
                    name:"opcion"+cont
                });
                $('#opcion4').attr({
                    name:"opcion"+cont
                });
            }
            else {
                alert('aqui');
                $('#opcion1').attr({
                    name:"opcion"+cont
                });
                $('#opcion2').attr({
                    name:"opcion"+cont
                });
                $('#opcion3').attr({
                    name:"opcion"+cont
                });
                $('#opcion4').attr({
                    name:"opcion"+cont
                });
            }
        }
        $('#preguntas'+numero).remove();
    });*/

    var formulario = document.querySelector("#agregarCurso"), //Tomo al formulario
    	mensajesError = $(".clsError");

    formulario.addEventListener("submit", function(event){ //Cuando se intente enviar los datos
        event.preventDefault(); //Cancelo el envío

        for(var cont = 0; cont < formulario.length; cont++){
            if(formulario[cont].value == ""){
                mensajesError.css('display','inline-block').text('Llene todos los campos para guardar.');
            }
            else {
                formulario.submit();
            }
        }
    }, false);
});
