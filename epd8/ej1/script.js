document.addEventListener("DOMContentLoaded", (event) => {
    // Pedir al usuario la cantidad de temperaturas
let totalTemperaturas = parseInt(prompt("Introduce el número total de temperaturas a recoger:"));

// Crear un array vacío para almacenar las temperaturas
let temperaturas = [];

// Recolectar las temperaturas
for (let i = 0; i < totalTemperaturas; i++) {
    let temp = parseFloat(prompt(`Introduce la temperatura ${i + 1}:`));
    temperaturas.push(temp);

    // Clasificar la temperatura
    if (temp > 30) {
        console.log(`La temperatura ${temp}°C es alta.`);
    } else if (temp >= 20 && temp <= 30) {
        console.log(`La temperatura ${temp}°C es media.`);
    } else {
        console.log(`La temperatura ${temp}°C es baja.`);
    }
}

// Calcular la temperatura máxima, mínima y media
let maxTemp = Math.max(...temperaturas);
let minTemp = Math.min(...temperaturas);
let suma = temperaturas.reduce((acum, temp) => acum + temp, 0);
let mediaTemp = suma / temperaturas.length;

// Imprimir resultados
console.log(`Temperatura máxima: ${maxTemp}°C`);
console.log(`Temperatura mínima: ${minTemp}°C`);
console.log(`Temperatura media: ${mediaTemp.toFixed(2)}°C`);

// Ordenar temperaturas ascendentemente y descendentemente
let temperaturasAsc = [...temperaturas].sort((a, b) => a - b);
let temperaturasDesc = [...temperaturas].sort((a, b) => b - a);

console.log("Temperaturas ordenadas ascendentemente:", temperaturasAsc);
console.log("Temperaturas ordenadas descendentemente:", temperaturasDesc);





  });
