<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="includes/style.css">

</head>
<body>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" id="navId">
    <li class="nav-item dropdown">
      
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Especies</a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="administrador/adm_especie.php">Administrar Especie</a>
        <a class="dropdown-item" href="administrador/regis_especie.php">Nueva Especie</a>
      </div>
    </li>

    <li class="nav-item dropdown">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Amigos</a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="administrador/adm_lista.php">Ver Árboles por Amigos</a>
        <a class="dropdown-item" href="administrador/adm_friends.php">Administar Árboles de Amigos </a>
        <a class="dropdown-item" href="administrador/adm_user.php">Administar Amigos< </a>
      </div>
    </li>
    
    <li class="nav-item dropdown">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Árboles</a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="administrador/regis_trees.php">Nuevo Árbol</a>
        <a class="dropdown-item" href="administrador/adm_trees.php">Administrar Árboles </a>
      </div>
    </li>
    <li class="nav-item">
      <a href="administrador/dashboard.php" class="nav-link">Dashboard</a>
    </li>
    <li class="nav-item">
      <a href="signUp.php" class="nav-link">Signup</a>
    </li>
    <li class="nav-item">
      <a href="index.php" class="nav-link">Login</a>
    </li>
    <li class="nav-item">
      <a href="logout.php" class="nav-link">Logout</a>
    </li>
  </ul>

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
