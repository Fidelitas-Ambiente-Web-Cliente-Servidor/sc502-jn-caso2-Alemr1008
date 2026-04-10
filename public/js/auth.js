$(function () {

    console.log("auth.js cargado ✅");

    $('#formLogin').submit(function (e) {
        e.preventDefault();
        e.stopPropagation();

        const username = $('#username').val();
        const password = $('#password').val();

        console.log("Enviando login...");

        $.ajax({
            url: '/sc502-jn-caso2-Alemr1008/index.php?page=login',
            method: 'POST',

            // 🔥 CAMBIO CLAVE
            dataType: 'text',

            data: {
                username: username,
                password: password
            },

            success: function (res) {
                console.log("RESPUESTA RAW:");
                console.log(res);
            },

            error: function (err) {
                console.error("Error AJAX:", err);
            }
        });

        return false;
    });

});