<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras | Éclat</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos generales */
        body {
            background: black;
            color: white;
            font-family: 'Arial', sans-serif;
        }

        /* Contenedor del carrito */
        .cart-container {
            max-width: 900px;
            margin: 5% auto;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.05); /* Fondo semitransparente */
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        /* Título */
        .cart-title {
            font-size: 2.5rem;
            text-align: center;
            font-weight: bold;
            margin-bottom: 2rem;
            letter-spacing: 2px;
        }

        /* Estilo de la tabla */
        .table {
            color: white;
            background: none; /* Fondo transparente */
        }

        .table th {
            text-transform: uppercase;
            font-size: 1rem;
            letter-spacing: 1px;
            border-bottom: 2px solid white;
        }

        .table td {
            vertical-align: middle;
        }

        .table input {
            color: black;
        }

        /* Botones */
        .btn-delete, .btn-update, .btn-checkout {
            background-color: #ffffff; /* Blanco */
            color: black;
            font-weight: bold;
            border: 1px solid #ddd; /* Borde gris claro */
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .btn-delete:hover, .btn-update:hover, .btn-checkout:hover {
            background-color: #f0f0f0; /* Gris claro al hover */
            color: black;
            border-color: #bbb; /* Borde gris oscuro */
        }

        /* Total */
        .total-container {
            text-align: right;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            margin-top: 1.5rem;
        }

        /* Texto adicional */
        .description {
            text-align: center;
            font-size: 1rem;
            color: #aaaaaa;
            margin-top: 1rem;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2 class="cart-title">Carrito de Compras</h2>
    <table class="table text-center">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de producto -->
            <tr>
                <td>Pintura Urbana</td>
                <td>$50.00</td>
                <td><input type="number" value="1" min="1" class="form-control" style="max-width: 80px;"></td>
                <td>$50.00</td>
                <td>
                    <button class="btn btn-update">Actualizar</button>
                    <button class="btn btn-delete">Eliminar</button>
                </td>
            </tr>
            <tr>
                <td>Fotografía Callejera</td>
                <td>$75.00</td>
                <td><input type="number" value="2" min="1" class="form-control" style="max-width: 80px;"></td>
                <td>$150.00</td>
                <td>
                    <button class="btn btn-update">Actualizar</button>
                    <button class="btn btn-delete">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Total -->
    <div class="total-container">
        Total: $200.00
    </div>

    <!-- Botón de pago -->
   <!-- Botón de pago modificado -->
    <a href="pago.html" class="btn btn-checkout mt-3 w-100">Proceder al Pago</a>


    <!-- Descripción adicional -->
    <p class="description">Revisa tus productos antes de realizar la compra. ¡Gracias por apoyar el arte!</p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>