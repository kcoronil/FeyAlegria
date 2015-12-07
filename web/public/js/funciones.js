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
    $('.fecha_nacimiento').not('.hasDatePicker').datepicker({
        format: "dd-mm-yyyy",
        startView: 2,
        language: "es",
        endDate: '+0d'
    });
    $('.fecha_nacimiento').on('change', function(){
        $('.datepicker').hide();
    });
    return false;
}
function agregar_form_anidados(boton){
    var collectionHolder = $('#' + boton.attr('data-target'));
    var prototype = collectionHolder.attr('data-prototype');
    var form = prototype.replace(/__name__/g, collectionHolder.children().length);
    collectionHolder.append(form);

    var collectionHolder2 = $('#' + boton.attr('id') +'-'+ (collectionHolder.children().length-1));

    var prototype2 = collectionHolder2.attr('data-prototype');
    var form2 = prototype2.replace(/__name__/g, collectionHolder2.children().length);
    collectionHolder2.append(form2);

    $('.fecha_nacimiento').not('.hasDatePicker').datepicker({
        format: "dd-mm-yyyy",
        startView: 2,
        language: "es",
        endDate: '+0d'
    });
    $('.fecha_nacimiento').on('change', function(){
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
    $(".fecha_nacimiento").datepicker({
        format: "dd-mm-yyyy",
        startView: 2,
        language: "es",
        endDate: '+0d'
    });
    $('.fecha_nacimiento').on('change', function(){
        $('.datepicker').hide();
    });
});
