/**
 * Created by edward on 18/06/15.
 */
// habilita botones para envio de formulario
$(document).ready(function() {
    $("form").change(function(){
        $('.botones_formulario').prop('disabled', false);
    });
});


function agregar_form(boton){
    var collectionHolder = $('#' + boton.attr('data-target'));
    var prototype = collectionHolder.attr('data-prototype');
    var form = prototype.replace(/__name__/g, collectionHolder.children().length);
    collectionHolder.append(form);
    $('.datepick').not('.hasDatePicker').datepicker({
        format: "yyyy/mm/dd",
        language:"es"
    });
    $('.datepick').on('change', function(){
        $('.datepicker').hide();
    });
    return false;
}

// funcion para actualizar nombres de campos de formularios despues de eliminar formularios
function renombrar_forms(div_formularios){
    var collectionHolder = $('#'+div_formularios);
    var length = collectionHolder.children().length;
    if (length) {
        for (var i = 1; i <= length; i++) {
            var divs_inputs = collectionHolder.children().eq(i-1).find(':input').length;
            var data_content = collectionHolder.children().eq(i-1).attr("data-content");
            for (var x = 1; x <= divs_inputs; x++){
                var nombre_input = collectionHolder.children().eq(i-1).find(':input').eq(x-1).attr('name');
                var id_input = collectionHolder.children().eq(i-1).find(':input').eq(x-1).attr('id');
                var nuevo_nombre = nombre_input.replace(']['+data_content+'][',']['+(i-1)+'][');
                var nuevo_id = id_input.replace('_'+data_content+'_','_'+(i-1)+'_');
                collectionHolder.children().eq(i-1).find(':input').eq(x-1).attr('name', nuevo_nombre);
                collectionHolder.children().eq(i-1).find(':input').eq(x-1).attr('id', nuevo_id);
            }
            collectionHolder.children().eq(i-1).find('a').attr('data-related', (i-1));
            collectionHolder.children().eq(i-1).attr('data-content', (i-1));
        }
    }
    return false;
}

function borrar_form(boton){
    var name = boton.attr('data-related');
    $('*[data-content="'+name+'"]').remove();
    return false;
}

// datepicker
$(function () {
    $(".datepick").datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        endDate: '+0d'
    });
    $('.datepick').on('change', function(){
        $('.datepicker').hide();
    });
});
