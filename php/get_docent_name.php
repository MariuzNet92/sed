<?php
// Configuración de conexión a la base de datos
$host = "127.0.0.1";
$user = "root";
$password = ""; // Cambiar si es necesario
$dbname = "bd_sed";

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión a la base de datos."]));
}

// Validar si se proporcionó el ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Consulta para obtener el nombre del docente
    $sql = "SELECT CONCAT(nombres, ' ', apellido_pat, ' ', apellido_mat) AS nombre FROM docent_adm WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(["success" => false, "message" => "Error al preparar la consulta."]));
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["success" => true, "name" => $row['nombre']]);
    } else {
        echo json_encode(["success" => false, "message" => "Personal no encontrado."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "ID no válido o no proporcionado."]);
}

$conn->close();
?>
