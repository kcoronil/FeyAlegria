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
    if(prototype){
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
    }
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

$(function () {
    $(".datepick").datepicker({
        format: "dd-mm-yyyy",
        language: "es",
        endDate: '+0d'
    });
    $('.datepick').on('change', function(){
        $('.datepicker').hide();
    });
});

$('#form_ajax').submit(function(e){
    e.preventDefault();
    var ajax_form = $('#form_ajax').closest('form');
    $.ajax({
        url: ajax_form.attr('action'),
        type: 'POST',
        data: ajax_form.serialize(),
        dataType: 'json'
        //crossDomain: true
    }).done(function () {
        var success = true;
        //var msj_container = wrap.find('> .w-form-done');
        //contact_form.toggle(!success);
        //msj_container.toggle(success);
    }).fail(function (response, textStatus, jqXHR) {
        //var success = false;
        //var msj_container = wrap.find('> .w-form-fail');
        //msj_container.toggle(!success);
    });
});

$(document).on('click', '.enlace_editar_estudiante', function(e){
    e.preventDefault();
    var form_container = $('#ajax_modal_content');
    $.ajax({
        url: $(this).data('url'),
        type: 'GET'
    }).done(function (data) {
        var success = true;
        form_container.html(data);
        if(form_container.find('.cont_curso').children().length<1) {
            agregar_form($('#curso-add'));
        }

    }).fail(function (response, textStatus, jqXHR) {
    });
});

$(document).on('click', '.enlace_quitar_representante', function(e){
    e.preventDefault();

    var apellido = $(this).closest('tr').find('td:eq(1)').text().trim();
    var nombre = $(this).closest('tr').find('td:eq(2)').text().trim();
    var url_redirect = $(this).data('redirect');
    console.log(url_redirect);
    if (confirm('Remover Item: '+nombre+' '+apellido+'\nEsta seguro?')){
        $.ajax({
            url: $(this).data('url'),
            type: 'GET'
        }).done(function() {
            location.href = url_redirect;
        }).fail(function (response, textStatus, jqXHR) {
        });
    }
    else{
        event.preventDefault();
    }
});

$(document).on('click', '.agregar_representante_existente', function(e){
    e.preventDefault();
    var list_container = $('#ajax_modal_content_list');
    $.ajax({
        url: $(this).data('url'),
        type: 'GET'
    }).done(function (data) {
        var success = true;
        list_container.html(data);
        if(list_container.find('.cont_curso').children().length<1) {
            agregar_form($('#curso-add'));
        }

    }).fail(function (response, textStatus, jqXHR) {
    });
});

$(document).on('click','.close_ajax_modal', function(e) {
    e.preventDefault();
    $('#ajax_modal_content').html('');
});