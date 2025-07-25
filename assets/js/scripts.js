let otpValidado = false;
$('form').on('submit', function(e) {
    if (!otpValidado) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Validación requerida',
            text: 'Debes validar el código OTP antes de enviar.',
            confirmButtonText: 'Aceptar'
        });
    }
});

$('#btnEnviarOTP').on('click', function() {
    const email = $('#email').val().trim();
    if (!email) return Swal.fire({
            icon: 'warning',
            title: 'Campo requerido',
            text: 'Por favor ingresa tu correo',
            confirmButtonText: 'Aceptar'
        });


    $.post('controller/Otp.php', { accion: 'enviar', email }, function(res) {
        if (res.success) {
            $('#correo-estado').text('OTP enviado correctamente').removeClass('hidden text-red-600').addClass('text-green-600');
            $('#otpSection').show();
        } else {
            $('#correo-estado').text(res.message || 'Error enviando OTP').removeClass('hidden text-green-600').addClass('text-red-600');
        }
    }, 'json');
});

$('#btnValidarOTP').on('click', function() {
    const email = $('#email').val().trim();
    const otp = $('#otp').val().trim();

    if (!otp) return Swal.fire({
        icon: 'warning',
        title: 'Por favor ingresa el OTP',
        text: 'OTP válido, puedes continuar.',
        confirmButtonText: 'Aceptar'
    });

    $.post('controller/Otp.php', { accion: 'validar', email, otp }, function(res) {
        if (res.success) {
            otpValidado = true;
            $('#otp-error').addClass('hidden');
            $('#btn-guardar').prop('disabled', false); // Este botón debe existir en tu HTML
            Swal.fire({
                icon: 'success',
                title: 'Validación exitosa',
                text: 'OTP válido, puedes continuar.',
                confirmButtonText: 'Aceptar'
            });

        } else {
            $('#otp-error').text(res.message).removeClass('hidden');
        }
    }, 'json');
});
function cargarFormulario(usuario, preguntas) {
    document.getElementById('nombre').value = usuario.nombre;
    document.getElementById('apellido').value = usuario.apellido;
    document.getElementById('telefono').value = usuario.telefono || '';
    document.getElementById('email').value = usuario.correo;

    preguntas.forEach(p => {
        const num = p.numero_pregunta;

        const preguntaSelect = document.getElementById(`pregunta${num}`);
        const respuestaInput = document.getElementById(`respuesta${num}`);
        const customContainer = document.getElementById(`custom-question${num}-container`);
        const customInput = document.getElementById(`custom-question${num}`);

        preguntaSelect.value = p.pregunta;

        if (p.pregunta === 'other') {
            customContainer.classList.remove('hidden');
            customInput.value = p.pregunta_personalizada || '';
        }

        respuestaInput.value = p.respuesta;
    });

    document.getElementById('info-existente').textContent = "Este usuario ya está registrado. Puedes revisar o actualizar sus datos.";

}
