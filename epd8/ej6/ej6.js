function getParametros(url) {
    const parsedUrl = new URL(url);
    const searchParams = new URLSearchParams(parsedUrl.search);

    const paramsObject = {};
    searchParams.forEach((value, key) => {
        paramsObject[key] = value;
    });

    return paramsObject;
}

const url = prompt("Introduce la URL que deseas analizar:", "https://192.168.1.55:80/PA?p1=1.55&p2=2.55&op=sumar");

if (url) {
    document.getElementById("url").textContent = url;

    const params = getParametros(url);

    console.log(params);

    const parametrosTable = document.getElementById("parametros");

    for (const [key, value] of Object.entries(params)) {
        const row = document.createElement('tr');

        const nameCell = document.createElement('td');
        nameCell.textContent = key;
        row.appendChild(nameCell);

        const valueCell = document.createElement('td');
        valueCell.textContent = value;
        row.appendChild(valueCell);

        parametrosTable.appendChild(row); // Agregar la fila al cuerpo de la tabla
    }

    if (Object.keys(params).length > 3) {
        alert("Demasiados parámetros");

    } else {

        params.total = 0.0;

        if (params.op === "sumar") {
            params.total = parseFloat(params.p1) + parseFloat(params.p2);
        }

        if (params.op === "restar") {
            params.total = parseFloat(params.p1) - parseFloat(params.p2);
            params.total = parseFloat(params.total.toFixed(2));
        }

        if (params.op === "multiplicar") {
            params.total = parseFloat(params.p1) * parseFloat(params.p2);
            params.total = parseFloat(params.total.toFixed(2));
        }

        const totalRow = document.createElement('tr');

        const totalNameCell = document.createElement('td');
        totalNameCell.textContent = "Total";
        totalRow.appendChild(totalNameCell);

        const totalValueCell = document.createElement('td');
        totalValueCell.textContent = params.total.toFixed(2);
        totalRow.appendChild(totalValueCell);

        parametrosTable.appendChild(totalRow);
    }
}
