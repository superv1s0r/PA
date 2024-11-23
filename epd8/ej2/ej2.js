function calcularDiferenciaHoras() {
    let hora1 = prompt("Introduce la primera hora (hh:mm:ss)");
    let hora2 = prompt("Introduce la segunda hora (hh:mm:ss)");

    let formatoHora = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/;

    if (!formatoHora.test(hora1) || !formatoHora.test(hora2)) {
        alert("HORA INCORRECTA");
        return;
    }

    let [h1, m1, s1] = hora1.split(":").map(Number);
    let [h2, m2, s2] = hora2.split(":").map(Number);

    let diferenciaSegundos = ((h2 * 3600 + m2 * 60 + s2) - (h1 * 3600 + m1 * 60 + s1));

    if (diferenciaSegundos <= 0) {
        alert("ERROR: La segunda hora introducida es anterior o igual a la primera.");
        return;
    }

    let horas = Math.floor(diferenciaSegundos / 3600);
    let minutos = Math.floor((diferenciaSegundos % 3600) / 60);
    let segundos = diferenciaSegundos % 60;

    alert(`Faltan: ${horas} horas, ${minutos} minutos y ${segundos} segundos.`);
}

calcularDiferenciaHoras();
