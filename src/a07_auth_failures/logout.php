<?php
session_start();
session_destroy();
header('Location: index.php?message=Has cerrado sesión correctamente.');
exit();
?>