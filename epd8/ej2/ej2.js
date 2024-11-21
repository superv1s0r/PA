function calcularDiferenciaHoras() {

    let hora1 = prompt("Introduce la primera hora en formato hh:mm:ss");
    let hora2 = prompt("Introduce la segunda hora en formato hh:mm:ss");

    let formatoHora = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/;

    if (!formatoHora.test(hora1) || !formatoHora.test(hora2)) {
        alert("HORA INCORRECTA");
        return;
    }

    let fechaBase = new Date();
    let [h1, m1, s1] = hora1.split(":");
    let [h2, m2, s2] = hora2.split(":");

    let tiempo1 = new Date(fechaBase.getFullYear(), fechaBase.getMonth(), fechaBase.getDate(), h1, m1, s1);
    let tiempo2 = new Date(fechaBase.getFullYear(), fechaBase.getMonth(), fechaBase.getDate(), h2, m2, s2);

    if (tiempo2 <= tiempo1) {
        alert("ERROR: La segunda hora introducida es anterior a la primera.");
        return;
    }

    let diferenciaMilisegundos = tiempo2 - tiempo1;

    let segundosTotales = Math.floor(diferenciaMilisegundos / 1000);
    let horas = Math.floor(segundosTotales / 3600);
    let minutos = Math.floor((segundosTotales % 3600) / 60);
    let segundos = segundosTotales % 60;

    alert("Faltan: " + horas + " horas, " + minutos + " minutos y " + segundos + " segundos.");
}

calcularDiferenciaHoras();
