<?php
require_once __DIR__.'/auth.php';
enforce_access(['rule' => 'authenticated']);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Solo Autenticados</title></head>
<body>
<h1>Solo Usuarios Autenticados</h1>
<p>Si ves esto estás logueado.</p>
</body>
</html>
