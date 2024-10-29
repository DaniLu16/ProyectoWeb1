<?php
include('../funciones.php');
require('../includes/header_us.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$connection = getConnection();

if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si se proporcionó un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id = intval($_GET['id']);

// Obtener los datos del usuario
$sql = "SELECT * FROM amigos WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Usuario no encontrado.");
}

$usuario = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    var_dump($_POST); // Verifica que los datos se están enviando
    echo "</pre>";

    // Validar los datos recibidos
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $pais = trim($_POST['pais']);
    $estado_id = isset($_POST['estado']) ? 1 : 0;
    $rol_id = intval($_POST['rol_id']);

    // Llamar a la función para editar el usuario
    if (editarUsuario($id, $nombre, $apellidos, $telefono, $email, $direccion, $pais, $estado_id, $rol_id)) {
        header("Location: success_page.php?mensaje=Usuario editado exitosamente.");
        exit();
    } else {
        echo "Error al editar el usuario.";
    }
}

?>

<div class="form-container">
    <h1 class="text-center">Editar Amigo</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
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
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
