(function ($) {
    $(document).ready(function () {
        $("#armar_cuadrado").text('hola');
    });

    $("#nombre").keyup(function () {
        var inputVal = $(this).val();
        var new_text = inputVal.replace(/[^a-zA-Z\s]/g, '');
        $('#nombre').val(new_text);
    });
}(jQuery));


// quiero crear una funcion que cuando apriete el boton mostrar
// que el div id armar_cuadrado
// tenga 100p x 100p y que sea azul

/*

        alert
        //console.info('hola mundo');
        //$("#armar_cuadrado").val('');
        $("#armar_cuadrado").text('hola');
        $("#miBoton").click(function () {
            alert("Hola");
        });

        $("#mostrar").click(function () {
            $('#armar_cuadrado').show();
        });
        //
        $("#ocultar").click(function () {
            $('#armar_cuadrado').hide();
        });

        $("#nombre").keyup(function () {
            var inputVal = $(this).val();
            var new_text = inputVal.replace(/[^a-zA-Z\s]/g, '');
            $('#nombre').val(new_text);
        });

*/
