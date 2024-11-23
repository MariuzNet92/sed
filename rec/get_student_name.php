<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db_conn = new mysqli('localhost', 'root', '', 'bd_sed');

    if ($db_conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']);
        exit;
    }

    $stmt = $db_conn->prepare("SELECT CONCAT(nombres, ' ', apellido_pat, ' ', apellido_mat) AS name FROM estudiantes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'name' => $row['name']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado']);
    }

    $stmt->close();
    $db_conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID no especificado']);
}
?>
