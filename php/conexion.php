<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "bd_sed";

$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
