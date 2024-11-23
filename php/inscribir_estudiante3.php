<?php
require_once 'conexion.php';

$carnet = $_POST['carnet'] ?? null;
$id_curso = $_POST['curso'] ?? null;
$paralelo = $_POST['paralelo'] ?? null;
$fecha_inscripcion = date("Y-m-d");

if (!$carnet || !$id_curso || !$paralelo) {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
    exit;
}

// Verificar si el estudiante existe
$queryEstudiante = "SELECT carnet_id FROM estudiantes WHERE carnet_id = ?";
$stmtEstudiante = $conexion->prepare($queryEstudiante);
$stmtEstudiante->bind_param("s", $carnet);
$stmtEstudiante->execute();
$resultadoEstudiante = $stmtEstudiante->get_result();

if ($resultadoEstudiante->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "El estudiante no existe."]);
    exit;
}

// Verificar si el estudiante ya está inscrito en el curso y paralelo
$queryCheck = "SELECT id FROM inscripciones WHERE rude = ? AND id_curso = ? AND paralelo = ?";
$stmtCheck = $conexion->prepare($queryCheck);
$stmtCheck->bind_param("sis", $carnet, $id_curso, $paralelo);
$stmtCheck->execute();
$resultadoCheck = $stmtCheck->get_result();

if ($resultadoCheck->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El estudiante ya está inscrito en este curso y paralelo."]);
    exit;
}

// Registrar la inscripción
$queryInsert = "INSERT INTO inscripciones (rude, id_curso, fecha_inscripcion, paralelo) 
                VALUES (?, ?, ?, ?)";
$stmtInsert = $conexion->prepare($queryInsert);
$stmtInsert->bind_param("siss", $carnet, $id_curso, $fecha_inscripcion, $paralelo);

if ($stmtInsert->execute()) {
    echo json_encode(["success" => true, "message" => "Inscripción realizada con éxito."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al registrar la inscripción."]);
}

$stmtInsert->close();
$stmtCheck->close();
$stmtEstudiante->close();
$conexion->close();
?>
