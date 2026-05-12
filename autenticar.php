<?php
session_start();
include("conexion.php");

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST["usuario"]) && !empty($_POST["usuario"]) &&
       isset($_POST["Contraseña"]) && !empty($_POST["Contraseña"])){
        
        $usuario = mysqli_real_escape_string($conectar, $_POST['usuario']);
        $password = mysqli_real_escape_string($conectar, $_POST['Contraseña']);
        
        $sql = "SELECT usuario, password FROM usuario WHERE usuario = '$usuario'";
        $resultado = mysqli_query($conectar, $sql);
        
        if(mysqli_num_rows($resultado) == 1) {
            $sesion = mysqli_fetch_array($resultado);
            
            // Verificar contraseña (asumiendo que está en texto plano en la BD)
            if($password == $sesion["password"]) {
                $_SESSION["usuario"] = $sesion["usuario"]; // ← CORREGIDO: Guardar usuario
                header("Location: index.php");
                exit();
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado";
        }
    } else {
        $error = "Por favor complete todos los campos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <Title>Gestor PC - Iniciar Sesión</Title>
    <link href="css/bootstrap.css" rel="stylesheet"/>
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Iniciar Sesión</h2>
        
        <form method="POST" action="autenticar.php">
            <div class="form-group mb-3">
                <label class="form-label">Usuario:</label>
                <input type="text" name="usuario" class="form-control" required autofocus>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label">Contraseña:</label>
                <input type="password" name="Contraseña" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger mt-3">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>
        
        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>