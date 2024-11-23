<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener datos del personal
$sql = "SELECT id, nombres, apellido_pat, apellido_mat, carnet_id, correo, telefono, cargo FROM docent_adm";
$resultado = $conexion->query($sql);

$staff = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $staff[] = $fila;
    }
}

echo json_encode($staff);

$conexion->close();
?>
