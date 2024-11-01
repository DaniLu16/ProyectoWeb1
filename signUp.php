<?php require('includes/header.php'); 
      require('funciones.php'); ?>

<body class="signup-background2">
    <div class="signup-container">
        <div class="form-wrapper"> <!-- Contenedor para el formulario -->
            <h1 class="text-center">Sign Up</h1>
            <form class="signup-form" action="register.php" method="POST" novalidate>
                <div class="form-row">
                    <div class="form-column"> <!-- Primera columna -->
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Enter your name" required>
                        </div>

                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Enter your last name" required>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Enter phone number" required pattern="[0-9]{10,15}">
                          
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        </div>

                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Enter address" required>
                        </div>

                    </div> <!-- Fin de la primera columna -->

                    <div class="form-column"> <!-- Segunda columna -->
                        <div class="form-group">
                            <label for="pais">País</label>
                            <select class="form-control" id="pais" name="pais" required>
                                <option value="">Selecciona un país</option>
                                <!-- Opciones de país se cargarán  -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="8">
                           
                        </div>

                        

                        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    </div> 
                </div> 
            </form>
        </div> 
    </div>

    
    <?php cargarPaises(); ?>
    <?php require('includes/footer.php'); ?>
</body>
