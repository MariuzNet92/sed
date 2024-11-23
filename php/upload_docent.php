<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Configuración
    $id = intval($_POST['id']);
    $uploads_dir_img = 'C:/xampp/htdocs/sed/bd/docentes/img/';
    $uploads_dir_docs = 'C:/xampp/htdocs/sed/bd/docentes/documents/';
    $db_conn = new mysqli('localhost', 'root', '', 'bd_sed');

    // Verificar la conexión a la base de datos
    if ($db_conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Error de conexión: " . $db_conn->connect_error]);
        exit;
    }

    // Inicializar variables
    $foto_name = null;
    $doc_names = [];

    // Procesar la foto (JPG)
    if (!empty($_FILES['foto']['name'])) {
        $tmp_name = $_FILES['foto']['tmp_name'];
        $foto_name = $id . '_foto.jpg';
        if (!move_uploaded_file($tmp_name, $uploads_dir_img . $foto_name)) {
            echo json_encode(["success" => false, "message" => "Error al subir la foto."]);
            exit;
        }
    }

    // Procesar los documentos (PDF)
    foreach ($_FILES as $key => $file) {
        if ($key !== 'foto' && $file['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $file['tmp_name'];
            $file_name = $id . '_' . $key . '.pdf';
            $doc_names[$key] = $file_name;
            if (!move_uploaded_file($tmp_name, $uploads_dir_docs . $file_name)) {
                echo json_encode(["success" => false, "message" => "Error al subir el archivo {$key}."]);
                exit;
            }
        }
    }

    // Construir consulta SQL
    $columns = "id, foto";
    $placeholders = "?, ?";
    $params = [$id, $foto_name];
    $types = "is";

    foreach ($doc_names as $key => $value) {
        $columns .= ", $key";
        $placeholders .= ", ?";
        $params[] = $value;
        $types .= "s";
    }

    $update_clause = "foto = VALUES(foto)";
    foreach (array_keys($doc_names) as $key) {
        $update_clause .= ", $key = VALUES($key)";
    }

    $sql = "INSERT INTO datos_docent ($columns) VALUES ($placeholders)
            ON DUPLICATE KEY UPDATE $update_clause";

    $stmt = $db_conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Archivos subidos y datos guardados exitosamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al guardar datos: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $db_conn->error]);
    }

    $db_conn->close();
}
?>
