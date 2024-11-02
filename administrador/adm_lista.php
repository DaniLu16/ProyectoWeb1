<?php
require('../includes/header_admin.php');
include('../funciones.php');
session_start();

// Verificar si el usuario es administrador (rol_id = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

$amigos = obtenerListaAmigos();
?>

<body class="signup-background2"> <!-- Añadido la clase aquí -->
<div class="container mt-5">
    <h2>Lista de Amigos</h2>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($amigo = mysqli_fetch_assoc($amigos)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($amigo['id']); ?></td>
                    <td><?php echo htmlspecialchars($amigo['nombre'] . " " . $amigo['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($amigo['email']); ?></td>
                    <td>
                        <a href="view_user.php?id=<?php echo $amigo['id']; ?>" class="btn btn-primary">Ver Árboles</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
