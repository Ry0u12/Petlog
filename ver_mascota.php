<?php
require_once 'database.php';

// Crear conexión
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mascota = null; // Inicializamos la variable para almacenar los datos de la mascota
$mensaje_error = null; // Inicializamos la variable para mensajes de error

// Verificar si se ha pasado un ID de mascota por la URL (método GET)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_mascota = $_GET['id'];

    // Preparar la consulta SQL para obtener los detalles de la mascota por su ID
    $sql = "SELECT * FROM mascotas WHERE id_mascota = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincular el parámetro
        $stmt->bind_param("i", $id_mascota); // "i" indica que el parámetro es un entero

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $mascota = $result->fetch_assoc();
        } else {
            // Si no se encuentra la mascota, puedes mostrar un mensaje de error
            $mensaje_error = "Mascota no encontrada.";
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        $mensaje_error = "Error al preparar la consulta: " . $conn->error;
    }
} else {
    // Si no se proporciona un ID válido, podemos mostrar un mensaje de error
    $mensaje_error = "ID de mascota no válido.";
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Mascota</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            color: #007bff;
        }
        #detalles_mascota {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        #detalles_mascota h2 {
            color: #28a745;
            margin-top: 0;
        }
        #detalles_mascota p {
            margin-bottom: 0;
            font-size: 0.9em;
        }
        #detalles_mascota strong {
            font-weight: bold;
        }
        #detalles_mascota a {
            color: #007bff;
            text-decoration: none;
        }
        #detalles_mascota a:hover {
            text-decoration: underline;
        }
        p.error {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
        .acciones {
            margin-top: 15px;
            display: flex;
            gap: 10px; /* Espacio entre los botones */
            align-items: center; /* Alineación vertical de los elementos */
        }
        .acciones a, .acciones button {
            background-color: #6c757d;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            border: none; /* Eliminar el borde predeterminado del botón */
            cursor: pointer; /* Cambiar el cursor al pasar por encima */
        }
        .acciones a:hover, .acciones button:hover {
            background-color: #0056b3;
        }
        .volver-index {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .volver-index a {
            background-color: #007bff; /* Color grisáceo para el botón de volver */
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
        }
        .volver-index a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="volver-index">
        <a href="index.php">Volver al Inicio</a>
    </div>
    <h1>Detalles de la Mascota</h1>

    <?php if (isset($mensaje_error)): ?>
        <p class="error"><?php echo $mensaje_error; ?></p>
    <?php elseif ($mascota): ?>
        <div id="detalles_mascota">
            <h2><?php echo htmlspecialchars($mascota['nombre']); ?></h2>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($mascota['id_mascota']); ?></p>
            <p><strong>Especie:</strong> <?php echo htmlspecialchars($mascota['especie']); ?></p>
            <p><strong>Raza:</strong> <?php echo htmlspecialchars($mascota['raza'] ?: 'No especificada'); ?></p>
            <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($mascota['fecha_nacimiento'] ?: 'No especificada'); ?></p>
            <p><strong>Sexo:</strong> <?php echo htmlspecialchars($mascota['sexo'] === 'M' ? 'Macho' : ($mascota['sexo'] === 'H' ? 'Hembra' : 'Desconocido')); ?></p>
            <p><strong>Color Principal:</strong> <?php echo htmlspecialchars($mascota['color_principal'] ?: 'No especificado'); ?></p>
            <p><strong>Color Secundario:</strong> <?php echo htmlspecialchars($mascota['color_secundario'] ?: 'No especificado'); ?></p>
            <p><strong>Señas Particulares:</strong> <?php echo htmlspecialchars($mascota['senas_particulares'] ?: 'Ninguna'); ?></p>
            <p><strong>Nombre del Propietario:</strong> <?php echo htmlspecialchars($mascota['nombre_propietario']); ?></p>
            <div class="acciones">
                <a href="modificar_mascota.php?id=<?php echo htmlspecialchars($mascota['id_mascota']); ?>">Modificar Datos</a>
            </div>
        </div>
    <?php else: ?>
        <p>No se ha especificado un ID de mascota.</p>
    <?php endif; ?>

</body>
</html>