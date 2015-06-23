/**
 * Created by edward on 18/06/15.
 */
// habilita botones para envio de formulario
$(document).ready(function() {
    $("form").change(function(){
        $('.botones_formulario').prop('disabled', false);
    });
});


// funcion para agregar formsets Simples
function agregar_formset(boton, contenedor, plantilla, formset){
    var count = contenedor.children().length;
    var tmplMarkup = plantilla.html();
    var compiledTmpl = tmplMarkup.replace(/__prefix__/g, count);
    contenedor.append(compiledTmpl);

    // update form count
    $('#id_'+formset+'_set-TOTAL_FORMS').attr('value', count+1);
    // some animate to scroll to view our new form
    $('html, body').animate({
        scrollTop: boton.position().top-200
    }, 800);
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
