<?php
require_once __DIR__.'/auth.php';
$user = get_current_user();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        nav a { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Bienvenido<?php echo $user ? ', '.htmlspecialchars($user['name']) : ''; ?></h1>
    <nav>
        <a href="index.php">Inicio</a>
        <?php if ($user): ?>
            <a href="profile.php">Perfil</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
        <a href="test_public.php">Pública</a>
        <a href="test_auth.php">Solo Autenticados</a>
        <a href="test_ids.php">Solo IDs</a>
        <a href="test_levels.php">Rango de Niveles</a>
        <a href="test_exception.php">Con Excepción</a>
    </nav>
</body>
</html>
