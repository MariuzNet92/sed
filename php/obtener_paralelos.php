<?php
require_once 'conexion.php';

$curso_id = $_GET['curso_id'] ?? null;

if (!$curso_id) {
    echo json_encode(["success" => false, "message" => "El ID del curso no fue proporcionado."]);
    exit;
}

$query = "SELECT DISTINCT paralelo FROM cursos WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $paralelos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $paralelos[] = $fila['paralelo'];
    }
    echo json_encode(["success" => true, "paralelos" => $paralelos]);
} else {
    echo json_encode(["success" => false, "message" => "No se encontraron paralelos para este curso."]);
}

$stmt->close();
$conexion->close();
?>
