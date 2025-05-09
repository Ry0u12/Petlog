<?php
require_once 'database.php';

// Crear conexión
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener todas las mascotas de la base de datos
$sql = "SELECT id_mascota, nombre, especie, raza FROM mascotas";
$result = $conn->query($sql);

$mascotas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mascotas[] = $row;
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnetización de Mascotas</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h1, h2 {
            color: #007bff;
        }
        #buscar-mascota {
            background-color: #e9ecef;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        #mascotas-recientes {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #lista-mascotas-recientes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        .mascota {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .mascota h3 {
            margin-top: 0;
            color: #28a745;
        }
        .mascota p {
            margin-bottom: 5px;
            font-size: 0.9em;
        }
        .mascota a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9em;
        }
        .mascota a:hover {
            text-decoration: underline;
        }
        #menu {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
        }
        #menu h2 {
            margin-top: 0;
            color: #6c757d;
            font-size: 1.2em;
        }
        #menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        #menu ul li {
            margin-bottom: 8px;
        }
        #menu ul li a {
            color: #007bff;
            text-decoration: none;
        }
        #menu ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Sistema de Carnetización de Mascotas</h1>

    <section id="buscar-mascota">
        <h2>Buscar Mascota (Próximamente)</h2>
    </section>

    <nav id="menu">
        <h2>Menú</h2>
        <ul>
            <li><a href="aniadir_mascota.php">Registrar Mascota</a></li>
        </ul>
    </nav>

    <section id="mascotas-recientes">
        <h2>Mascotas Registradas</h2>
        <div id="lista-mascotas-recientes">
            <?php if (!empty($mascotas)): ?>
                <?php foreach ($mascotas as $mascota): ?>
                    <div class="mascota">
                        <h3><?php echo htmlspecialchars($mascota['nombre']); ?></h3>
                        <p>Especie: <?php echo htmlspecialchars($mascota['especie']); ?></p>
                        <p>Raza: <?php echo htmlspecialchars($mascota['raza'] ?: 'No especificada'); ?></p>
                        <a href="ver_mascota.php?id=<?php echo htmlspecialchars($mascota['id_mascota']); ?>">Ver Detalles</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay mascotas registradas.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>