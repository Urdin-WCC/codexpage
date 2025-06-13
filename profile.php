<?php
require_once __DIR__.'/auth.php';
require_login();
$user = current_user();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = get_db_connection();
    $fields = ['email','phone','name'];
    $updates = [];
    $params = [];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) {
            $updates[] = "$f = ?";
            $params[] = $_POST[$f];
        }
    }
    if (!empty($_POST['new_password'])) {
        if (password_verify($_POST['current_password'] ?? '', $user['password'])) {
            $updates[] = "password = ?";
            $params[] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        } else {
            $error = 'Contraseña actual incorrecta';
        }
    }
    if (!$error) {
        if ($updates) {
            $params[] = $user['id'];
            $sql = 'UPDATE users SET '.implode(',',$updates).' WHERE id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            header('Location: profile.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
</head>
<body>
    <h1>Editar Perfil</h1>
    <?php if (isset($error)) echo '<p style="color:red">'.$error.'</p>'; ?>
    <form method="post">
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></label><br>
        <label>Teléfono: <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"></label><br>
        <label>Nombre: <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required></label><br>
        <label>Contraseña Actual: <input type="password" name="current_password"></label><br>
        <label>Nueva Contraseña: <input type="password" name="new_password"></label><br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
