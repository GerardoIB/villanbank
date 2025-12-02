$(document).ready(function() {
    $('#btnRegister').click(function(event) {
        event.preventDefault();

        // Leemos los valores
        var name = $('#inputName').val().trim();
        var paterno = $('#inputPaterno').val().trim();
        var materno = $('#inputMaterno').val().trim();
        var phone = $('#inputPhone').val().trim();
        var password = $('#inputPassword').val().trim();
        var confirmPassword = $('#inputConfirmPassword').val().trim();
        var gender = $('#inputGender').val();
        var birthday = $('#inputBirthday').val().trim();

        // Validamos
        var validate = validateFields(name, paterno, materno, phone, password, confirmPassword, gender, birthday);

        if (validate) {
            // Enviamos a procesar
            validateRegistration(true, name, paterno, materno, phone, password, gender, birthday);
        }
    });

    function validateFields(name, paterno, materno, phone, password, confirmPassword, gender, birthday) {
        var status = true;

        // (Las validaciones visuales de texto rojo se quedan igual, son útiles)
        if (name == '') { $('#nameHelp').text('El nombre es obligatorio.').show(); status = false; } else { $('#nameHelp').hide(); }
        if (paterno == '') { $('#paternoHelp').text('El apellido paterno es obligatorio.').show(); status = false; } else { $('#paternoHelp').hide(); }
        if (materno == '') { $('#maternoHelp').text('El apellido materno es obligatorio.').show(); status = false; } else { $('#maternoHelp').hide(); }

        if (phone == '' || !/^[0-9]{10}$/.test(phone)) { $('#phoneHelp').text('El teléfono debe tener 10 dígitos.').show(); status = false; } else { $('#phoneHelp').hide(); }

        if (password == '') { $('#passwordHelp').text('La contraseña es obligatoria.').show(); status = false; } else { $('#passwordHelp').hide(); }

        if (confirmPassword == '' || password !== confirmPassword) { $('#confirmPasswordHelp').text('Las contraseñas no coinciden.').show(); status = false; } else { $('#confirmPasswordHelp').hide(); }

        if (gender == null || gender == '') { $('#genderHelp').text('Debe seleccionar un género.').show(); status = false; } else { $('#genderHelp').hide(); }

        if (birthday == '') { $('#birthdayHelp').text('La fecha de nacimiento es obligatoria.').show(); status = false; } else { $('#birthdayHelp').hide(); }

        return status;
    }

    function validateRegistration(validate, name, paterno, materno, phone, password, gender, birthday) {
        if (validate == true) {
            const infoRegister = [name, paterno, materno, phone, password, gender, birthday];
            processRegistration(infoRegister);
        }
    }

    // --- AQUÍ ESTÁ EL CAMBIO IMPORTANTE ---
    function processRegistration(infoRegister) {
        var url = BASE_URL + "/auth/register_process";

        $.ajax({
            type: "POST",
            url: url,
            data: { 'infoRegister': infoRegister },
            dataType: 'json',
            success: function(response) {
                if (response.status == 1) {
                    // ✅ ÉXITO CON SWEETALERT
                    Swal.fire({
                        icon: 'success',
                        title: '¡Registro Exitoso!',
                        text: response.message, // "Cuenta bancaria creada..."
                        confirmButtonText: 'Ir a Iniciar Sesión',
                        allowOutsideClick: false // Obliga al usuario a dar click en el botón
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirige al login al dar click
                            window.location.href = "http://localhost/pagina/auth/auth";
                        }
                    });

                } else {
                    // ❌ ERROR DE LÓGICA (Ej: Teléfono duplicado)
                    Swal.fire({
                        icon: 'error',
                        title: 'Hubo un problema',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                // ❌ ERROR DE SERVIDOR (Ej: 404, 500)
                console.error("Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Comunicación',
                    text: 'No se pudo conectar con el servidor. Intente más tarde.'
                });
            }
        });
    }
});