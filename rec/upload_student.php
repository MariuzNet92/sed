<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $uploads_dir_img = 'C:/xampp/htdocs/sed/bd/estudiantes/img/';
    $uploads_dir_docs = 'C:/xampp/htdocs/sed/bd/estudiantes/documents/';
    $db_conn = new mysqli('localhost', 'root', '', 'bd_sed');

    if ($db_conn->connect_error) {
        die("Error de conexión: " . $db_conn->connect_error);
    }

    // Inicializar nombres de archivos
    $foto_name = null;
    $doc_names = [
        'carnet_id' => null,
        'certif_nac' => null,
        'doc_1' => null,
        'doc_2' => null
    ];

    // Procesar la foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['foto']['tmp_name'];
        $foto_name = $id . '_foto.jpg';
        move_uploaded_file($tmp_name, $uploads_dir_img . $foto_name);
    }

    // Procesar los documentos
    foreach ($doc_names as $field => &$file_name) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES[$field]['tmp_name'];
            $file_name = $id . '_' . $field . '.pdf';
            move_uploaded_file($tmp_name, $uploads_dir_docs . $file_name);
        }
    }

    // Insertar rutas en la base de datos
    $stmt = $db_conn->prepare(
        "INSERT INTO datos_student (id, foto, carnet_id, certif_nac, doc_1, doc_2) 
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        foto=?, carnet_id=?, certif_nac=?, doc_1=?, doc_2=?"
    );

    $stmt->bind_param(
        "issssssssss",
        $id,
        $foto_name,
        $doc_names['carnet_id'],
        $doc_names['certif_nac'],
        $doc_names['doc_1'],
        $doc_names['doc_2'],
        $foto_name,
        $doc_names['carnet_id'],
        $doc_names['certif_nac'],
        $doc_names['doc_1'],
        $doc_names['doc_2']
    );

    if ($stmt->execute()) {
        echo "Archivos subidos y datos guardados exitosamente.";
    } else {
        echo "Error al guardar datos: " . $stmt->error;
    }

    $stmt->close();
    $db_conn->close();
}
?>
