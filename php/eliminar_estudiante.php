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

// Verificar si se envió el ID
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Consulta para eliminar el estudiante
    $sql = "DELETE FROM estudiantes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Estudiante eliminado correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo eliminar el estudiante."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "ID no proporcionado."]);
}

$conn->close();
?>
