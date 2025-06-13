<?php
require_once __DIR__.'/auth.php';
enforce_access(['rule' => 'public']);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Publica</title></head>
<body>
<h1>Página Pública</h1>
<p>Accesible por cualquiera.</p>
</body>
</html>
