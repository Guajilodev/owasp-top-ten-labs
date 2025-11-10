<?php

$log_file = 'log.txt';
$action_time = date('Y-m-d H:i:s');

// VULNERABILIDAD: Registro insuficiente.
// Se registra la acción, pero faltan detalles críticos como el usuario que la realizó, su IP, etc.
$log_entry = "[{$action_time}] Acción crítica realizada.\n";

file_put_contents($log_file, $log_entry, FILE_APPEND);

header('Location: index.php?message=Acción crítica realizada. Revisa los logs.');
exit();

?>