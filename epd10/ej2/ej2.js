//Al cargar el dom y los elmnts
$(document).ready(function() {
    // Variable global para almacenar el número a adivinar
    let numeroAdivinar;
    let intentos = 0;

    // Función para iniciar el juego
    function iniciarJuego() {
        generarNumeroAdivinar();
        intentos = 0;

        // Habilitar botón de envío
        $('#enviarSuposicion').prop('disabled', false);

        // Limpiar campos y resultados
        $('#suposicion').val('');
        $('#resultado').text('').removeClass('correcto error pista');

        // Asignar eventos
        $('#enviarSuposicion').on('click', verificarSuposicion);
        $('#reiniciarJuego').on('click', reiniciarJuego);
        
        // Validación de entrada en tiempo real
        $('#suposicion').on('blur', function() {
            comprobarEntrada();
        });
    }

    // Genera un número aleatorio entre 1 y 100
    function generarNumeroAdivinar() {
        numeroAdivinar = Math.floor(Math.random() * 100) + 1;
    }

    // la validez de la entrada
    function comprobarEntrada() {
        const suposicion = $('#suposicion').val().trim();
        const numero = parseInt(suposicion);

        if (suposicion === '' || isNaN(numero) || numero < 1 || numero > 100) {
            $('#suposicion').addClass('error');
            $('#enviarSuposicion').prop('disabled', true);
            return false;
        } else {
            $('#suposicion').removeClass('error');
            $('#enviarSuposicion').prop('disabled', false);
            return true;
        }
    }

    // Verificar la suposición del usuario
    function verificarSuposicion() {
        if (!comprobarEntrada()) return;

        const suposicion = parseInt($('#suposicion').val());
        intentos++;

        if (suposicion === numeroAdivinar) {
            mostrarResultado(`¡Felicidades! Adivinaste el número en ${intentos} intentos.`, 'correcto');
            desactivarBotonEnviar();
        } else if (suposicion < numeroAdivinar) {
            mostrarResultado('Demasiado bajo. ¡Intenta de nuevo!', 'pista');
        } else {
            mostrarResultado('Demasiado alto. ¡Intenta de nuevo!', 'pista');
        }
    }

    // Mostrar resultado con efectos de animación
    function mostrarResultado(mensaje, claseResultado) {
        $('#resultado')
            .hide()
            .text(mensaje)
            .removeClass('correcto error pista')
            .addClass(claseResultado)
            .fadeIn(500)
            .css({
                'color': claseResultado === 'correcto' ? 'green' : 
                         claseResultado === 'error' ? 'red' : 'blue'
            });
    }

    // Desactivar botón de envío
    function desactivarBotonEnviar() {
        $('#enviarSuposicion').prop('disabled', true)
            .animate({opacity: 0.5}, 500);
    }

    // Reiniciar el juego
    function reiniciarJuego() {
        iniciarJuego();
        $('#resultado')
            .fadeOut(300, function() {
                $(this).text('').removeClass('correcto error pista');
            });
    }

    // Iniciar el juego cuando la página se carga
    iniciarJuego();
});
