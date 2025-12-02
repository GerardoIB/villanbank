$(document).ready(function() {
    $('#btnLogin').click(function() {
        var phone = $('#inputPhone').val().trim();
        var password = $('#inputPassword').val().trim();
        var validate = validateFields(phone, password);

        validateLogin(validate, phone, password);

        console.log("Validate: " + validate);
    });

    function validateFields(phone, password) {
        var status = true;

        if (phone == '') {
            $('#phoneHelp').show();
            status = false;
        } else if (phone.replace(/\D/g, '') !== phone) {
            $('#phoneHelp').show();
            status = false;
        } else {
            $('#phoneHelp').hide();
        }

        if (password == '') {
            $('#passwordHelp').show();
            status = false;
        } else {
            $('#passwordHelp').hide();
        }

        return status;
    }

    function validateLogin(validate, phone, password) {
        if (validate == true) {
            const infoLogin = [phone, password];
            processLogin(infoLogin);
        }
    }

    function processLogin(infoLogin) {
        var url = "http://localhost/pagina/auth/login";

        $.ajax({
            type: "POST",
            url: url,
            data: { 'infoLogin': infoLogin },
            dataType: 'json', // Especificamos que esperamos JSON
            success: function(data) { // 'data' ya es un objeto JSON
                var status = data['status'];
                console.log("Response server: " + status);

                if (status == 1) {
                    // Éxito

                    // 1. Obtenemos el nombre del nivel que mandó PHP
                    var levelName = data['levelName'];

                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido! (Nivel: ' + levelName + ')',
                        text: 'Has iniciado sesión correctamente. Redirigiendo...',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // URL Dinámica (leída del JSON)
                        window.location.href = data['redirectUrl'];
                    });

                } else if (status == 0) {
                    // Credenciales incorrectas
                    Swal.fire({
                        icon: 'error',
                        title: 'Credenciales incorrectas',
                        text: 'El número de teléfono o la contraseña son incorrectos.',
                        showConfirmButton: false,
                        timer: 1500
                    });

                } else {
                    // ¡NUEVO! Cualquier otro error (status 2, 3, etc.)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Servidor',
                        // Muestra el mensaje que enviamos desde PHP
                        text: data['message'] || 'Ocurrió un error inesperado.',
                        showConfirmButton: true
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // ¡IMPLEMENTADO! Esto se activa si PHP crashea (Error 500) o hay error de red
                console.error("Error AJAX:", textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Comunicación',
                    text: 'No se pudo conectar con el servidor. Revisa tu conexión o intenta más tarde.',
                    showConfirmButton: true
                });
            }
        });
    }
});
