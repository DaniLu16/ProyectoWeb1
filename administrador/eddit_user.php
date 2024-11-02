<body class="signup-background2"> <!-- Cambiado a signup-background2 -->
<?php
include('../funciones.php');
include('../includes/header_admin.php');

// Inicializar la variable de error
$error = '';

// Verificar si se ha enviado el formulario con el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $pais = $_POST['pais'];
    $rol_id = $_POST['rol_id'];
    $estado_id = $_POST['estado_id'];

    // Llamar a la función para editar el usuario
    $resultado = editarUsuario($id, $nombre, $apellidos, $telefono, $email, $direccion, $pais, $estado_id, $rol_id);

    // Verificar el resultado de la operación
    if ($resultado['success']) {
        header("Location: adm_user.php?msg=" . urlencode($resultado['message']));
        exit;
    } else {
        $error = $resultado['message']; // Se asume que el error está en el mensaje
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del usuario desde la base de datos
    $connection = getConnection();
    $query = "SELECT * FROM amigos WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si se encontró el usuario
    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
    } else {
        echo "Usuario no encontrado.";
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    echo "ID no especificado.";
    exit;
}
?>

<div class="form-container">
    <h1 class="text-center">Editar Usuario</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="eddit_user.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pais">País:</label>
                <input type="text" name="pais" value="<?php echo htmlspecialchars($usuario['pais']); ?>" required>
            </div>
            <div class="form-group">
                <label for="rol_id">Rol ID:</label>
                <input type="number" name="rol_id" value="<?php echo htmlspecialchars($usuario['rol_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="estado_id">Estado ID:</label>
                <input type="number" name="estado_id" value="<?php echo htmlspecialchars($usuario['estado_id']); ?>" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
</body>
