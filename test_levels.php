<?php
require_once __DIR__.'/auth.php';
enforce_access(['rule' => 'range', 'min' => 30, 'max' => 70]);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Rango de Niveles</title></head>
<body>
<h1>Solo para Niveles 30-70</h1>
<p>Usuarios fuera de este rango o no autenticados no deben ver esto.</p>
</body>
</html>
