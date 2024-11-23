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

// Consulta para obtener estudiantes
$sql = "SELECT * FROM estudiantes";
$result = $conn->query($sql);

$estudiantes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $estudiantes[] = $row;
    }
}

echo json_encode($estudiantes);

$conn->close();
?>
