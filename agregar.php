<?php
session_start();
include("conexion.php");
include("computadora.php");
// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: autenticar.php");
    exit();
}

// Pasar la conexión al constructor de Computadora
$computadora = new Computadora($conectar);  // ← AGREGADO: pasar $conectar
$mensaje = '';
$error = '';

// Calcular siguiente ID automático
$sql_siguiente_id = "SELECT COALESCE(MAX(id), 0) + 1 as siguiente_id FROM computadora";
$result = mysqli_query($conectar, $sql_siguiente_id);
$row = mysqli_fetch_assoc($result);
$siguiente_id = $row['siguiente_id'];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Datos de la computadora
    $datos_pc = [
        'id' => $_POST['id'],
        'Marca' => mysqli_real_escape_string($conectar, $_POST['Marca']),
        'Procesador' => mysqli_real_escape_string($conectar, $_POST['Procesador']),
        'Ubicacion' => mysqli_real_escape_string($conectar, $_POST['Ubicacion']),
        'Tipo' => mysqli_real_escape_string($conectar, $_POST['Tipo']),
        'Sistema' => mysqli_real_escape_string($conectar, $_POST['Systema']), // Nota: 'Systema' en formulario, 'Sistema' en BD
        'Estado' => mysqli_real_escape_string($conectar, $_POST['Estado']),
        'fecha_del_ultimo_mantenimiento' => $_POST['fecha_del_ultimo_mantenimiento'],
        'ram' => mysqli_real_escape_string($conectar, $_POST['ram']),
        'capacidad' => mysqli_real_escape_string($conectar, $_POST['capacidad'])
    ];
    
    // 1. Agregar computadora
    $resultado = $computadora->agregar($datos_pc);
    
    if ($resultado['success']) {
        $pc_id = $resultado['id'];  // Usar el ID devuelto por mysqli_insert_id()
        
        // 2. Asignar responsable (si se proporcionó)
        if (!empty($_POST['Carnet']) && !empty($_POST['Nombre_Completo'])) {
            $carnet = mysqli_real_escape_string($conectar, $_POST['Carnet']);
            $nombre = mysqli_real_escape_string($conectar, $_POST['Nombre_Completo']);
            $cargo = isset($_POST['Cargo']) ? mysqli_real_escape_string($conectar, $_POST['Cargo']) : '';
            
            $computadora->asignarResponsable(
                $pc_id,
                $carnet,
                $nombre,
                $cargo
            );
        }
        
        // 3. Asignar impresora (si se proporcionó)
        if (!empty($_POST['impresora_Marca'])) {
            $impresora_marca = mysqli_real_escape_string($conectar, $_POST['impresora_Marca']);
            $impresora_modelo = isset($_POST['impresora_Modelo']) ? mysqli_real_escape_string($conectar, $_POST['impresora_Modelo']) : '';
            $impresora_estado = isset($_POST['impresora_estado']) ? mysqli_real_escape_string($conectar, $_POST['impresora_estado']) : 'Activa';
            
            $computadora->asignarImpresora(
                $pc_id,
                $impresora_marca,
                $impresora_modelo,
                $impresora_estado
            );
        }
        
        $mensaje = 'Computadora registrada exitosamente con ID: ' . $pc_id;
        // Actualizar siguiente ID
        $siguiente_id = $pc_id + 1;
    } else {
        $error = ' Error: ' . $resultado['message'];
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Computadora</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .container { max-width: 900px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .section-title { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; color: #007bff; }
        .alert { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4"> Agregar Nueva Computadora</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Cambiar action a agregar.php -->
        <form method="POST" action="agregar.php">
            <!-- Sección: Datos de la Computadora -->
            <div class="mb-4">
                <h3 class="section-title"> Datos de la Computadora</h3>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ID (Número único):</label>
                        <input type="number" name="id" class="form-control" 
                               value="<?php echo $siguiente_id; ?>" required 
                               min="1" readonly>
                        <small class="text-muted">ID automático generado por el sistema</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Marca:</label>
                        <input type="text" name="Marca" class="form-control" 
                               placeholder="Dell, HP, Lenovo, etc." required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Procesador:</label>
                        <input type="text" name="Procesador" class="form-control" 
                               placeholder="Intel Core i5, AMD Ryzen 7, etc.">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">RAM:</label>
                        <input type="text" name="ram" class="form-control" 
                               placeholder="8GB, 16GB DDR4, etc.">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Capacidad de Almacenamiento:</label>
                        <input type="text" name="capacidad" class="form-control" 
                               placeholder="256GB SSD, 1TB HDD, etc.">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo:</label>
                        <select name="Tipo" class="form-control">
                            <option value="pc">PC</option>
                            <option value="Laptop">Laptop</option>
                            <option value="Servidor">Servidor</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sistema Operativo:</label>
                        <input type="text" name="Systema" class="form-control" 
                               placeholder="Windows 10 Pro, Linux Ubuntu, etc.">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ubicación:</label>
                        <input type="text" name="Ubicacion" class="form-control" 
                               placeholder="Oficina 101, Sala de Servidores, etc.">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estado:</label>
                        <select name="Estado" class="form-control">
                            <option value="Activo">Activo</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Dañado">Dañado</option>
                            <option value="Baja">Baja</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha Último Mantenimiento:</label>
                        <input type="date" name="fecha_del_ultimo_mantenimiento" class="form-control">
                    </div>
                </div>
            </div>
            
            <!-- Sección: Responsable -->
            <div class="mb-4">
                <h3 class="section-title"> Responsable</h3>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Carnet/Identificación:</label>
                        <input type="text" name="Carnet" class="form-control" 
                               placeholder="EMP001, 123456, etc.">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre Completo:</label>
                        <input type="text" name="Nombre_Completo" class="form-control" 
                               placeholder="Juan Pérez López">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cargo:</label>
                        <input type="text" name="Cargo" class="form-control" 
                               placeholder="Analista de Sistemas, Gerente, etc.">
                    </div>
                </div>
            </div>
            
            <!-- Sección: Impresora -->
            <div class="mb-4">
                <h3 class="section-title"> Impresora Asociada</h3>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Marca de Impresora:</label>
                        <input type="text" name="impresora_Marca" class="form-control" 
                               placeholder="HP, Epson, Brother, etc.">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Modelo Impresora:</label>
                        <input type="text" name="impresora_Modelo" class="form-control" 
                               placeholder="LaserJet Pro, WorkForce, etc.">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estado Impresora:</label>
                        <select name="impresora_estado" class="form-control">
                            <option value="Activa">Activa</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Dañada">Dañada</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                     Guardar Computadora
                </button>
                <a href="index.php" class="btn btn-secondary btn-lg px-5"> Volver al Inicio</a>
            </div>
        </form>
    </div>
</body>
</html>