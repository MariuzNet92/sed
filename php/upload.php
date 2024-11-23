<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docente_id = $_POST['docente_id'];

    // Directorios de destino
    $imgDir = "C:/xampp/htdocs/sed/bd/docentes/img/";
    $docDir = "C:/xampp/htdocs/sed/bd/docentes/documents/";

    // Crear directorios si no existen
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (!is_dir($docDir)) mkdir($docDir, 0777, true);

    // Subir foto
    if (isset($_FILES['foto'])) {
        $fotoPath = $imgDir . $docente_id . '.jpg';
        move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath);
    }

    // Subir documentos PDF
    $docFiles = [
        'carnet_id', 'certif_nac', 'doc_1', 'doc_2', 'doc_3', 'doc_4', 'doc_5',
        'doc_6', 'doc_7', 'doc_8', 'doc_9', 'doc_10'
    ];

    foreach ($docFiles as $fileKey) {
        if (isset($_FILES[$fileKey])) {
            $docPath = $docDir . $docente_id . '_' . $fileKey . '.pdf';
            move_uploaded_file($_FILES[$fileKey]['tmp_name'], $docPath);
        }
    }

    echo json_encode(["status" => "success", "message" => "Archivos subidos correctamente."]);
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>
