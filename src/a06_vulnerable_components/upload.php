<?php

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// VULNERABILIDAD: Lista negra de extensiones fácilmente evitable.
// Un atacante puede usar doble extensión (ej. .php.jpg), caracteres nulos, o manipular el MIME type.
$blacklist = array("php", "phtml", "php3", "php4", "php5", "php7", "phps", "exe", "sh", "bat");

if(isset($_POST["submit"])) {
    // Esto no se usa en este lab, pero es común en formularios.
}

// Comprobar si el archivo ya existe
if (file_exists($target_file)) {
    header('Location: index.php?message=Lo siento, el archivo ya existe.');
    $uploadOk = 0;
}

// Comprobar la extensión del archivo (lista negra vulnerable)
if(in_array($imageFileType, $blacklist)) {
    header('Location: index.php?message=Lo siento, los archivos de tipo .' . $imageFileType . ' no están permitidos.');
    $uploadOk = 0;
}

// Si todo está bien, intentar subir el archivo
if ($uploadOk == 0) {
    // Mensaje ya enviado por la cabecera
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        header('Location: index.php?message=El archivo ' . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . ' ha sido subido con éxito. Puedes acceder a él en: ' . htmlspecialchars($target_file));
    } else {
        header('Location: index.php?message=Lo siento, hubo un error al subir tu archivo.');
    }
}
exit();

?>
