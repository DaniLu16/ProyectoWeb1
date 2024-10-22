<?php
//include('funciones.php');
//require('includes/header.php');
$error_msg = isset($_GET['error']) ? $_GET['error'] : '';
?>


<div class="login-container">
    <form class="login-form" action="login.php" method="POST">
        <h2 class="text-center">Login</h2>
        
        <?php if ($error_msg): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_msg); ?></div>
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

