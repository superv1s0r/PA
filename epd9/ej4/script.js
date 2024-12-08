const colores = [
    'red', 'blue', 'green', 'yellow', 'purple', 'orange'
];

let colorCorrecto;

function iniciarJuego() {
    reiniciarJuego();
    mostrarColorAleatorio();
    crearOpcionesDeColor();
}

function reiniciarJuego() {
    document.getElementById('resultado').textContent = '';
    const opciones = document.getElementById('opciones');
    opciones.innerHTML = '';
}

function mostrarColorAleatorio() {
    colorCorrecto = Math.floor(Math.random() * colores.length);
    const colorMostrado = document.getElementById('colorMostrado');
    colorMostrado.style.backgroundColor = colores[colorCorrecto];
}

function crearOpcionesDeColor() {
    const opciones = document.getElementById('opciones');
    const coloresAleatorios = [...colores];
    
    for (let i = coloresAleatorios.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [coloresAleatorios[i], coloresAleatorios[j]] = [coloresAleatorios[j], coloresAleatorios[i]];
    }
    
    coloresAleatorios.forEach(color => {
        const opcion = document.createElement('li');
        opcion.style.backgroundColor = color;
        opcion.addEventListener('click', verificarSeleccion);
        opciones.appendChild(opcion);
    });
}

function verificarSeleccion(event) {
    const resultado = document.getElementById('resultado');
    const colorSeleccionado = event.target.style.backgroundColor;
    
    if (colorSeleccionado === colores[colorCorrecto]) {
        resultado.textContent = '¡Correcto!';
        resultado.style.color = 'green';
    } else {
        resultado.textContent = 'Has fallado. ¡Inténtalo de nuevo!';
        resultado.style.color = 'red';
    }
    
    setTimeout(iniciarJuego, 2000);
}

document.addEventListener('DOMContentLoaded', iniciarJuego);