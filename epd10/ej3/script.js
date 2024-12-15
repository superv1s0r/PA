$(document).ready(function() {
    // Inicializar LazyLoad
    $(".lazy").lazyload({
        effect: "fadeIn",
        threshold: 200
    });

    // Función para medir tiempo de carga
    function medirTiempoCarga() {
        const metodos = [
            { nombre: 'Carga Normal', selector: '#imagenes-normales img' },
            { nombre: 'LazyLoad jQuery', selector: '#imagenes-lazyload img' },
            { nombre: 'LazyLoad HTML5', selector: '#imagenes-html5 img' }
        ];

        metodos.forEach(metodo => {
            const inicio = performance.now();
            const imagenes = document.querySelectorAll(metodo.selector);
            
            imagenes.forEach(img => {
                if (img.complete) {
                    const fin = performance.now();
                    console.log(`${metodo.nombre} - Tiempo de carga: ${fin - inicio} ms`);
                } else {
                    img.onload = () => {
                        const fin = performance.now();
                        console.log(`${metodo.nombre} - Tiempo de carga: ${fin - inicio} ms`);
                    };
                }
            });
        });
    }

    // Llamar a la función de medición de tiempo
    medirTiempoCarga();
});