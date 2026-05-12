<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$bd   = "gestor";

// Crear conexión
$conectar = mysqli_connect($host, $user, $pass, $bd);

// Verificar conexión
if (!$conectar) {
    die("Error de conexión: " . mysqli_connect_error());
}

// FUNCIONES BÁSICAS PARA USAR EN TODO EL SISTEMA

// Ejecutar consultas tipo INSERT, UPDATE, DELETE
function ejecutarConsulta($sql) {
    global $conectar;
    return mysqli_query($conectar, $sql);
}

// Ejecutar consultas tipo SELECT
function obtenerDatos($sql) {
    global $conectar;
    return mysqli_query($conectar, $sql);
}

// Obtener último ID insertado
function ultimoIdInsertado() {
    global $conectar;
    return mysqli_insert_id($conectar);
}
?>
