
<?php
require('../includes/header_amigo.php');
include('../funciones.php');
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener los árboles comprados por el usuario logueado
$user_id = $_SESSION['user_id'];
$result = obtenerArbolesCompradosPorUsuario($user_id);
?>

<body class="signup-background2">
<div class="container mt-5">
    <div class="form-wrapper">
        <h2>Mis Árboles Comprados</h2>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Comercial</th>
                    <th>Nombre Científico</th>
                    <th>Ubicación</th>
                    <th>Precio</th>
                    <th>Tamaño</th>
                    <th>Fecha de Compra</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($arbol = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($arbol['id']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['nombre_comercial']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['nombre_cientifico']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['ubicacion']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['precio']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['tamano']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['fecha_compra']); ?></td>
                        <td>
                            <img src="../arboles/<?php echo htmlspecialchars($arbol['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($arbol['nombre_comercial']); ?>" style="width: 100px; height: auto;">
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
