<?php
require_once 'database.php';

// Crear conexión
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensaje = ""; // Variable para mostrar mensajes al usuario
$registro_exitoso = false; // Variable para controlar la redirección

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $mensaje = "Por favor, complete los campos obligatorios (Nombre, Especie, Nombre del Propietario)."; // Mensaje actualizado
    } else {
        $sql = "INSERT INTO mascotas (nombre, especie, raza, fecha_nacimiento, sexo, color_principal, color_secundario, senas_particulares, nombre_propietario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Eliminada foto_url
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssssssss", $nombre, $especie, $raza, $fecha_nacimiento, $sexo, $color_principal, $color_secundario, $senas_particulares, $nombre_propietario); // Eliminada foto_url
            if ($stmt->execute()) {
                $mensaje = "Mascota registrada con éxito.";
                $registro_exitoso = true; // Marcar el registro como exitoso
            } else {
                $mensaje = "Error al registrar la mascota: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensaje = "Error al preparar la consulta: " . $conn->error;
        }
    }
}

$conn->close();

// Redireccionar al index si el registro fue exitoso
if ($registro_exitoso) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mascota</title>
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
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button[type="submit"]:hover {
            background-color: #218838;
        }
        p {
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }
        p.error {
            color: red;
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
    <h1>Registrar Nueva Mascota</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="<?php echo (strpos($mensaje, 'Error') === false) ? 'success' : 'error'; ?>"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div>
            <label for="especie">Especie:</label>
            <input type="text" id="especie" name="especie" required>
        </div>
        <div>
            <label for="raza">Raza (opcional):</label>
            <input type="text" id="raza" name="raza">
        </div>
        <div>
            <label for="fecha_nacimiento">Fecha de Nacimiento (opcional):</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento">
        </div>
        <div>
            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo">
                <option value="M">Macho</option>
                <option value="H">Hembra</option>
                <option value="">Desconocido</option>
            </select>
        </div>
        <div>
            <label for="color_principal">Color Principal:</label>
            <input type="text" id="color_principal" name="color_principal">
        </div>
        <div>
            <label for="color_secundario">Color Secundario (opcional):</label>
            <input type="text" id="color_secundario" name="color_secundario">
        </div>
        <div>
            <label for="senas_particulares">Señas Particulares (opcional):</label>
            <textarea id="senas_particulares" name="senas_particulares"></textarea>
        </div>
        <div>
            <label for="nombre_propietario">Nombre del Propietario:</label>
            <input type="text" id="nombre_propietario" name="nombre_propietario" required>
        </div>
        <button type="submit">Registrar Mascota</button>
    </form>
</body>
</html>