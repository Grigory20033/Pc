<?php
echo "<h3>Test de Conexión MySQL</h3>";

// Probar diferentes configuraciones
$configuraciones = [
    ['root', 'lacorrecta'],
    ['root', '1234'],
    ['root', 'password'],
    ['root', ''],  // Sin contraseña
    ['root', 'root']
];

foreach ($configuraciones as $config) {
    list($user, $pass) = $config;
    
    echo "<p>Probando: usuario='$user', pass='$pass'... ";
    
    try {
        $pdo_test = new PDO("mysql:host=127.0.0.1", $user, $pass);
        echo "<span style='color:green;'>✅ CONEXIÓN EXITOSA</span>";
        
        // Verificar si existe la base de datos
        $stmt = $pdo_test->query("SHOW DATABASES LIKE 'gestor'");
        if ($stmt->rowCount() > 0) {
            echo " | Base de datos 'gestor' existe";
        } else {
            echo " | Base de datos 'gestor' NO existe";
        }
        
    } catch(PDOException $e) {
        echo "<span style='color:red;'>❌ ERROR: " . $e->getMessage() . "</span>";
    }
    
    echo "</p>";
}
?>