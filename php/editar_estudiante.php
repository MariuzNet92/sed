<?php
// Configuración de conexión a la base de datos
$host = "127.0.0.1";
$user = "root";
$password = ""; // Cambiar si es necesario
$dbname = "bd_sed";

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se envían los datos necesarios
if (
    isset($_POST['id']) &&
    isset($_POST['nombres']) &&
    isset($_POST['apellido_pat']) &&
    isset($_POST['apellido_mat']) &&
    isset($_POST['carnet_id']) &&
    isset($_POST['fecha_nac']) &&
    isset($_POST['correo']) &&
    isset($_POST['telefono']) &&
    isset($_POST['direccion']) &&
    isset($_POST['genero'])
) {
    $id = intval($_POST['id']);
    $nombres = $_POST['nombres'];
    $apellido_pat = $_POST['apellido_pat'];
    $apellido_mat = $_POST['apellido_mat'];
    $carnet_id = $_POST['carnet_id'];
    $fecha_nac = $_POST['fecha_nac'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['genero'];

    // Consulta para actualizar el estudiante
    $sql = "UPDATE estudiantes SET nombres=?, apellido_pat=?, apellido_mat=?, carnet_id=?, fecha_nac=?, correo=?, telefono=?, direccion=?, genero=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $nombres, $apellido_pat, $apellido_mat, $carnet_id, $fecha_nac, $correo, $telefono, $direccion, $genero, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Estudiante actualizado correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar el estudiante."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
}

$conn->close();
?>
