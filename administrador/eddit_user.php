<?php
include('../funciones.php');
require('../includes/header_us.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$connection = getConnection();
if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id = intval($_GET['id']);

// Obtener usuario de la base de datos
$sql = "SELECT * FROM amigos WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Usuario no encontrado.");
}

$usuario = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar datos del formulario
    $nombre = trim($_POST['nombre']);
    // ...captura de otros campos...

    // Depuración
    echo '<pre>' . print_r($_POST, true) . '</pre>'; 

    // Preparar la consulta de actualización
    $updateSql = "UPDATE amigos SET nombre = ?, apellidos = ?, telefono = ?, email = ?, direccion = ?, pais = ?, estado_id = ?, rol_id = ? WHERE id = ?";
    $updateStmt = mysqli_prepare($connection, $updateSql);

    if (!$updateStmt) {
        die("Error en la preparación de la consulta: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($updateStmt, 'ssssssiii', $nombre, $apellidos, $telefono, $email, $direccion, $pais, $estado, $rol_id, $id);

    if (mysqli_stmt_execute($updateStmt)) {
        $affectedRows = mysqli_stmt_affected_rows($updateStmt);
        if ($affectedRows > 0) {
            header('Location: index.php?msg=' . urlencode('Usuario actualizado correctamente'));
            exit;
        } else {
            echo "No se realizaron cambios. Verifica que los datos sean diferentes.";
        }
    } else {
        echo "Error al ejecutar la consulta: " . mysqli_stmt_error($updateStmt);
    }

    mysqli_stmt_close($updateStmt);
}

mysqli_stmt_close($stmt); // Cierra el primer statement
mysqli_close($connection); // Cierra la conexión
?>

<div class="form-container">
    <h1 class="text-center">Editar Amigo</h1>
    <form method="POST" class="user-form">
        <div class="form-row">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
            </div>
            <div class="form-group">
                <label>País</label>
                <input type="text" name="pais" value="<?php echo htmlspecialchars($usuario['pais']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Estado</label>
                <input type="checkbox" name="estado" <?php echo $usuario['estado_id'] == 1 ? 'checked' : ''; ?>>
                <span>Activo</span>
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="rol_id" required>
                    <option value="1" <?php echo $usuario['rol_id'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                    <option value="2" <?php echo $usuario['rol_id'] == 2 ? 'selected' : ''; ?>>Amigo</option>
                </select>
            </div>
        </div>
        <form method="POST" class="user-form">
    <!-- Campos del formulario aquí -->
    <button type="submit" class="submit-btn">Guardar Cambios</button>
</form>
    </form>
</div>
