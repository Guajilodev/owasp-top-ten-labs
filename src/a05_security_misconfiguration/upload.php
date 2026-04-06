<?php

$target_dir = "file_uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// VULNERABILIDAD: Lista negra de extensiones fácilmente evitable.
// Una configuración segura usaría una whitelist de extensiones permitidas.
$blacklist = array("php", "phtml", "php3", "php4", "php5", "php7", "phps", "exe", "sh", "bat");

// Comprobar si el archivo ya existe
if (file_exists($target_file)) {
    header('Location: upload_form.php?message=Lo siento, el archivo ya existe.');
    $uploadOk = 0;
}

// Comprobar la extensión del archivo (blacklist vulnerable)
if (in_array($imageFileType, $blacklist)) {
    header('Location: upload_form.php?message=Lo siento, los archivos de tipo .' . $imageFileType . ' no están permitidos.');
    $uploadOk = 0;
}

// Si todo está bien, intentar subir el archivo
if ($uploadOk == 0) {
    // Mensaje ya enviado por la cabecera
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        header('Location: upload_form.php?message=El archivo ' . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . ' ha sido subido con éxito.');
    } else {
        header('Location: upload_form.php?message=Lo siento, hubo un error al subir tu archivo.');
    }
}
exit();
