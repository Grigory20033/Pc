<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: autenticar.php");
    exit();
}

include("conexion.php"); // aquí ya tienes $conectar

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $rol = $_POST['role'];

    $sql = "INSERT INTO usuario (usuario, password, role) VALUES ('$usuario', '$password', '$rol')";
    if (mysqli_query($conectar, $sql)) {
        $mensaje = "Usuario registrado correctamente";
    } else {
        $mensaje = "Error: " . mysqli_error($conectar);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nuevo Usuario</title>
    <link href="css/bootstrap.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Nuevo Usuario</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-control">
                <option value="admin">Administrador</option>
                <option value="user">Usuario</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Registrar</button>
    </form>

    <?php if (!empty($mensaje)) { ?>
        <div class="alert alert-info mt-3"><?php echo $mensaje; ?></div>
    <?php } ?>
</div>
</body>
</html>
