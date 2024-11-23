<?php
require_once 'conexion.php';

$carnet = $_GET['ci'] ?? null;

if (!$carnet) {
    echo json_encode(["success" => false, "message" => "Carnet de identidad no proporcionado."]);
    exit;
}

$query = "SELECT CONCAT(nombres, ' ', apellido_pat, ' ', apellido_mat) AS nombre 
          FROM estudiantes WHERE carnet_id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $carnet);
$stmt->execute();
$resultado = $stmt->get_result();

if ($fila = $resultado->fetch_assoc()) {
    echo json_encode(["success" => true, "nombre" => $fila['nombre']]);
} else {
    echo json_encode(["success" => false, "message" => "Estudiante no encontrado."]);
}

$stmt->close();
$conexion->close();
?>
