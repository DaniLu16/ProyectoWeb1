<?php
session_start();
include('funciones.php');  // Incluir el archivo con las funciones
require('includes/header.php');

// Detectar si hay un mensaje de error
$error_msg = isset($_GET['error']) ? $_GET['error'] : '';


?>

<body class="login-background">
    <div class="login-container">
        <button type="button" class="start-now" onclick="openModal()">Start Now</button>
    </div>



    
    <!-- Modal -->
    <div id="loginModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="login-container">
            <form class="login-form" action="login.php" method="POST">
                <h1 class="text-center">Login</h1>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
</div>


    <!-- Cargar el script externo -->
    <?php renderModalScript($error_msg); ?>

</body>