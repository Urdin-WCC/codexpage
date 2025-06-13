<?php
require_once __DIR__.'/auth.php';
// IDs permitidos, ej. usuarios nivel 33 y 66
$allowed_ids = [2,3];
enforce_access(['rule' => 'ids', 'ids' => $allowed_ids]);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>IDs Específicos</title></head>
<body>
<h1>Solo para IDs Específicos</h1>
<p>Acceso permitido solo a ciertos usuarios.</p>
</body>
</html>
