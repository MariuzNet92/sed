<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$rude = $_POST['estudiante'];
$id_curso = $_POST['curso'];
$paralelo = $_POST['paralelo'];
$fecha_inscripcion = date("Y-m-d");

// Verificar si el estudiante ya está inscrito en algún curso
$sql_check = "SELECT * FROM inscripciones WHERE rude = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $rude);
$stmt_check->execute();
$resultado_check = $stmt_check->get_result();

if ($resultado_check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El estudiante ya está inscrito en un curso y no puede inscribirse nuevamente."]);
} else {
    $sql = "INSERT INTO inscripciones (rude, id_curso, paralelo, fecha_inscripcion) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("siss", $rude, $id_curso, $paralelo, $fecha_inscripcion);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al inscribir al estudiante: " . $conexion->error]);
    }

    $stmt->close();
}

$stmt_check->close();
$conexion->close();
?>
