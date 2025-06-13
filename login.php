<?php
require_once __DIR__.'/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (login($email, $password)) {
        $redirect = $_GET['redirect'] ?? 'index.php';
        header('Location: '.$redirect);
        exit;
    } else {
        $error = 'Credenciales incorrectas';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) echo '<p style="color:red">'.$error.'</p>'; ?>
    <form method="post">
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Contraseña: <input type="password" name="password" required></label><br>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
