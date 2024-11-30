let inventario = [
    { nombre: "PC", precio: 899.99, cantidad: 15 },
    { nombre: "Teléfono", precio: 299.99, cantidad: 25 },
    { nombre: "Tablet", precio: 199.99, cantidad: 20 },
    { nombre: "Teclado", precio: 49.99, cantidad: 50 },
    { nombre: "Monitor", precio: 249.99, cantidad: 30 },
    { nombre: "Ratón", precio: 29.99, cantidad: 75 },
    { nombre: "Auriculares", precio: 79.99, cantidad: 40 },
    { nombre: "Impresora", precio: 199.99, cantidad: 15 },
    { nombre: "Disco Duro", precio: 129.99, cantidad: 50 },
    { nombre: "Tarjeta Gráfica", precio: 599.99, cantidad: 10 }
];


function mostrarInventario() {
    let mensaje = "Inventario Inicial:\n";
    inventario.forEach(producto => {
        mensaje += `${producto.nombre}: ${producto.precio}€ - ${producto.cantidad} unidades\n`;
    });
    return mensaje;
}


function agregarProducto() {
    const nombre = prompt("Ingrese el nombre del nuevo producto:");
    const precio = parseFloat(prompt("Ingrese el precio del nuevo producto:"));
    const cantidad = parseInt(prompt("Ingrese la cantidad disponible:"));

    if (nombre && !isNaN(precio) && !isNaN(cantidad)) {
        inventario.push({ nombre, precio, cantidad });
        alert("Producto agregado con éxito.");
    } else {
        alert("Datos de producto inválidos.");
    }
}


function actualizarInventario() {
    const nombre = prompt("Ingrese el nombre del producto a actualizar:");
    const producto = inventario.find(p => p.nombre === nombre);

    if (producto) {
        const nuevaCantidad = parseInt(prompt(`Ingrese la nueva cantidad para ${nombre}:`));
        if (!isNaN(nuevaCantidad)) {
            producto.cantidad = nuevaCantidad;
            alert("Producto actualizado con éxito.");
        } else {
            alert("Cantidad inválida.");
        }
    } else {
        alert("Producto no encontrado en el inventario.");
    }
}


function menuInventario() {
    while (true) {
        alert(mostrarInventario());
        const opcion = prompt(`¿Qué operación desea realizar?
1. Mostrar inventario
2. Agregar producto
3. Actualizar inventario
4. Salir
Opción:`);

        switch (opcion) {
            case "1":
                alert(mostrarInventario());
                break;
            case "2":
                agregarProducto();
                break;
            case "3":
                actualizarInventario();
                break;
            case "4":
                return;
            default:
                alert("Opción no válida. Intente de nuevo.");
        }
    }
}


menuInventario();