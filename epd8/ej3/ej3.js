function modificarCadena() {

    let cadena = prompt("Introduce una cadena de texto:");

    if (!cadena) {
        alert("No se introdujo una cadena válida.");
        return;
    }

    let resultado = "";

    for (let i = 0; i < cadena.length; i++) {
        let caracter = cadena.charAt(i); 

        if (i % 2 === 0) { 
            caracter = caracter.toUpperCase();
        }
        if (i % 2 === 0 && i % 5 === 0) { 
            caracter = "#";
        }
        if (i % 2 === 0 && i % 2 === 0) { 
            caracter = caracter.toLowerCase();
        }
        resultado += caracter; 
    }

    alert("Cadena modificada: " + resultado);
}

modificarCadena();
