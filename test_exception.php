<?php
require_once __DIR__.'/auth.php';
enforce_access(['rule' => 'range', 'min' => 30, 'max' => 70, 'except_ids' => [2]]);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Con Excepción</title></head>
<body>
<h1>Rango 30-70 con excepción</h1>
<p>El usuario con ID 2 está excluido.</p>
</body>
</html>
