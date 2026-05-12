<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: autenticar.php");
    exit();
}

include("conexion.php"); // aquí ya tienes $conectar

// Clase Eliminar dentro del mismo archivo
class Eliminar {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function eliminarComputadora($id) {
        $sql = "DELETE FROM computadora WHERE id = $id";
        return mysqli_query($this->conexion, $sql);
    }

    public function eliminarResponsable($carnet) {
        $sql = "DELETE FROM responsable WHERE Carnet = '$carnet'";
        return mysqli_query($this->conexion, $sql);
    }

    public function eliminarImpresora($id) {
        $sql = "DELETE FROM impresoras WHERE id = $id";
        return mysqli_query($this->conexion, $sql);
    }
}

// Crear objeto Eliminar usando la conexión
$eliminar = new Eliminar($conectar);

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['id_pc'])) {
        if ($eliminar->eliminarComputadora($_POST['id_pc'])) {
            $mensaje = "Computadora eliminada correctamente";
        } else {
            $error = "Error al eliminar computadora";
        }
    }

    if (!empty($_POST['carnet'])) {
        if ($eliminar->eliminarResponsable($_POST['carnet'])) {
            $mensaje = "Responsable eliminado correctamente";
        } else {
            $error = "Error al eliminar responsable";
        }
    }

    if (!empty($_POST['id_impresora'])) {
        if ($eliminar->eliminarImpresora($_POST['id_impresora'])) {
            $mensaje = "Impresora eliminada correctamente";
        } else {
            $error = "Error al eliminar impresora";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar registros</title>
    <link href="css/bootstrap.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
    <h2>Eliminar registros</h2>

    <!-- Formulario para eliminar computadora -->
    <form method="POST" class="mb-3">
        <label>ID Computadora:</label>
        <input type="number" name="id_pc" class="form-control" required>
        <button type="submit" class="btn btn-danger mt-2">Eliminar Computadora</button>
    </form>

    <!-- Formulario para eliminar responsable -->
    <form method="POST" class="mb-3">
        <label>Carnet Responsable:</label>
        <input type="text" name="carnet" class="form-control" required>
        <button type="submit" class="btn btn-danger mt-2">Eliminar Responsable</button>
    </form>

    <!-- Formulario para eliminar impresora -->
    <form method="POST" class="mb-3">
        <label>ID Impresora:</label>
        <input type="number" name="id_impresora" class="form-control" required>
        <button type="submit" class="btn btn-danger mt-2">Eliminar Impresora</button>
    </form>

    <!-- Mensajes -->
    <?php if (!empty($mensaje)) { ?>
        <div class="alert alert-success mt-3"><?php echo $mensaje; ?></div>
    <?php } ?>
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
    <?php } ?>
</div>
</body>
</html>
