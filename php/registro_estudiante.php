<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$nombres = $_POST['nombres'];
$apellido_pat = !empty($_POST['apellido_pat']) ? $_POST['apellido_pat'] : null;
$apellido_mat = !empty($_POST['apellido_mat']) ? $_POST['apellido_mat'] : null;
$carnet_id = $_POST['carnet_id'];
$fecha_nac = $_POST['fecha_nac'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$genero = $_POST['genero'];

// Verificar si el estudiante ya existe por el carnet de identidad
$sql_check = "SELECT * FROM estudiantes WHERE carnet_id = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $carnet_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "El estudiante con este CI ya está registrado.",
    ]);
    exit();
}

$sql = "INSERT INTO estudiantes (nombres, apellido_pat, apellido_mat, carnet_id, fecha_nac, correo, telefono, direccion, genero) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssss", $nombres, $apellido_pat, $apellido_mat, $carnet_id, $fecha_nac, $correo, $telefono, $direccion, $genero);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Estudiante registrado exitosamente.",
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error al registrar el estudiante: " . $conexion->error,
    ]);
}

$stmt->close();
$conexion->close();
?>
