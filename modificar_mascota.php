<?php
require_once 'database.php';

// Crear conexión
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensaje = "";
$mensaje_error = "";
$mascota = null;
$actualizacion_exitosa = false; // Variable para controlar la redirección

// Procesar el formulario de modificación (si se envía por POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_mascota']) && is_numeric($_POST['id_mascota'])) {
        $id_mascota_modificar = $_POST['id_mascota'];
        $nombre = $_POST["nombre"];
        $especie = $_POST["especie"];
        $raza = $_POST["raza"] ?? null;
        $fecha_nacimiento = $_POST["fecha_nacimiento"] ?? null;
        $sexo = $_POST["sexo"] ?? null;
        $color_principal = $_POST["color_principal"] ?? null;
        $color_secundario = $_POST["color_secundario"] ?? null;
        $senas_particulares = $_POST["senas_particulares"] ?? null;
        $nombre_propietario = $_POST["nombre_propietario"]; // Cambiado a nombre

        if (empty($nombre) || empty($especie) || empty($nombre_propietario)) {
            $mensaje_error = "Por favor, complete los campos obligatorios (Nombre, Especie, Nombre del Propietario)."; // Mensaje actualizado
        } else {
            $sql_update = "UPDATE mascotas SET nombre=?, especie=?, raza=?, fecha_nacimiento=?, sexo=?, color_principal=?, color_secundario=?, senas_particulares=?, nombre_propietario=? WHERE id_mascota=?"; // Eliminada foto_url
            $stmt_update = $conn->prepare($sql_update);

            if ($stmt_update) {
                $stmt_update->bind_param("sssssssssi", $nombre, $especie, $raza, $fecha_nacimiento, $sexo, $color_principal, $color_secundario, $senas_particulares, $nombre_propietario, $id_mascota_modificar); // Eliminada foto_url
                if ($stmt_update->execute()) {
                    $mensaje = "Datos de la mascota actualizados con éxito.";
                    $actualizacion_exitosa = true; // Marcar la actualización como exitosa
                } else {
                    $mensaje_error = "Error al actualizar los datos de la mascota: " . $stmt_update->error;
                }
                $stmt_update->close();
            } else {
                $mensaje_error = "Error al preparar la consulta de actualización.";
            }
        }
    } else {
        $mensaje_error = "ID de mascota no válido para la modificación.";
    }
}
// Obtener los datos de la mascota si se proporciona un ID (para mostrar el formulario)
elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_mascota = $_GET['id'];
    $sql_select = "SELECT * FROM mascotas WHERE id_mascota = ?";
    $stmt_select = $conn->prepare($sql_select);

    if ($stmt_select) {
        $stmt_select->bind_param("i", $id_mascota);
        $stmt_select->execute();
        $result_select = $stmt_select->get_result();

        if ($result_select->num_rows == 1) {
            $mascota = $result_select->fetch_assoc();
        } else {
            $mensaje_error = "Mascota no encontrada.";
        }
        $stmt_select->close();
    } else {
        $mensaje_error = "Error al preparar la consulta para obtener la mascota.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['id'])) {
    $mensaje_error = "Por favor, selecciona una mascota para modificar.";
}

$conn->close();

// Redireccionar a ver_mascota.php si la actualización fue exitosa
if ($actualizacion_exitosa) {
    header("Location: ver_mascota.php?id=" . urlencode($_POST['id_mascota']));
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Mascota</title>
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
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: calc(100% - 12px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        p.mensaje {
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }
        p.error {
            color: red;
            margin-top: 10px;
            font-weight: bold;
        }
        .volver-index {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .volver-index a {
            background-color: #007bff;
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
    <h1>Modificar Datos de Mascota</h1>

    <?php if ($mensaje_error): ?>
        <p class="error"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>

    <?php if ($mensaje): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if ($mascota): ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" name="id_mascota" value="<?php echo htmlspecialchars($mascota['id_mascota']); ?>">
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($mascota['nombre']); ?>" required>
            </div>
            <div>
                <label for="especie">Especie:</label>
                <input type="text" id="especie" name="especie" value="<?php echo htmlspecialchars($mascota['especie']); ?>" required>
            </div>
            <div>
                <label for="raza">Raza (opcional):</label>
                <input type="text" id="raza" name="raza" value="<?php echo htmlspecialchars($mascota['raza'] ?? ''); ?>">
            </div>
            <div>
                <label for="fecha_nacimiento">Fecha de Nacimiento (opcional):</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($mascota['fecha_nacimiento'] ?? ''); ?>">
            </div>
            <div>
                <label for="sexo">Sexo:</label>
                <select id="sexo" name="sexo">
                    <option value="M" <?php if ($mascota['sexo'] === 'M') echo 'selected'; ?>>Macho</option>
                    <option value="H" <?php if ($mascota['sexo'] === 'H') echo 'selected'; ?>>Hembra</option>
                    <option value="" <?php if (empty($mascota['sexo'])) echo 'selected'; ?>>Desconocido</option>
                </select>
            </div>
            <div>
                <label for="color_principal">Color Principal:</label>
                <input type="text" id="color_principal" name="color_principal" value="<?php echo htmlspecialchars($mascota['color_principal'] ?? ''); ?>">
            </div>
            <div>
                <label for="color_secundario">Color Secundario (opcional):</label>
                <input type="text" id="color_secundario" name="color_secundario" value="<?php echo htmlspecialchars($mascota['color_secundario'] ?? ''); ?>">
            </div>
            <div>
                <label for="senas_particulares">Señas Particulares (opcional):</label>
                <textarea id="senas_particulares" name="senas_particulares"><?php echo htmlspecialchars($mascota['senas_particulares'] ?? ''); ?></textarea>
            </div>
            <div>
                <label for="nombre_propietario">Nombre del Propietario:</label>
                <input type="text" id="nombre_propietario" name="nombre_propietario" value="<?php echo htmlspecialchars($mascota['nombre_propietario']); ?>" required>
            </div>
            <button type="submit">Guardar Cambios</button>
        </form>
    <?php elseif (!empty($_GET['id'])): ?>
        <p>No se encontraron los detalles de la mascota con el ID proporcionado.</p>
    <?php else: ?>
        <p>Por favor, selecciona una mascota para modificar.</p>
    <?php endif; ?>

</body>
</html>