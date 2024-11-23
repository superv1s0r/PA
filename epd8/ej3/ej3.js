function modificarCadena() {
    let cadena = prompt("Introduce una cadena de texto:");

    if (!cadena) {
        alert("No se introdujo una cadena válida.");
        return;
    }

    let resultado = "";

    for (let i = 0; i < cadena.length; i++) {
        let caracter = cadena.charAt(i);

        if (i % 5 === 0) { 
            resultado += "#"; 
        } else if (i % 2 === 0) { 
            resultado += caracter.toLowerCase();
        } else {
            resultado += caracter.toUpperCase();
        }
    }

    alert("Cadena modificada: " + resultado);
}

modificarCadena();
