<?php
require('../includes/header_admin.php');
include('../funciones.php');
session_start();

// Verificar si el usuario es administrador (rol_id = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Obtener todos los árboles comprados por todos los amigos
$arbolesCompradosPorAmigos = obtenerArbolesCompradosPorAmigos();
?>

<body class="signup-background2"> <!-- Añadido la clase aquí -->
<div class="container mt-5">
    <h2>Árboles Comprados por Amigos</h2>

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID Amigo</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>ID Árbol</th>
                <th>Nombre Comercial</th>
                <th>Nombre Científico</th>
                <th>Ubicación</th>
                <th>Precio</th>
                <th>Fecha de Compra</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($arbolesCompradosPorAmigos)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['amigo_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($row['arbol_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_comercial']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_cientifico']); ?></td>
                    <td><?php echo htmlspecialchars($row['ubicacion']); ?></td>
                    <td><?php echo htmlspecialchars($row['precio']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_compra']); ?></td>
                    <td>
                        <img src="../arboles/<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($row['nombre_comercial']); ?>" style="width: 100px; height: auto;">
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="/administrador/adm_lista.php" class="btn btn-secondary">Volver a la lista de amigos</a>
</div>
</body>
