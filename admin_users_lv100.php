<?php
require_once __DIR__.'/auth.php';
enforce_access(['rule' => 'range', 'min' => 100, 'max' => 100]);
$user = current_user();
=======
$user = get_current_user();
if (!$user || $user['level'] != 100) {
    enforce_access(['rule' => 'ids', 'ids' => []]); // will redirect
}

$pdo = get_db_connection();

if (isset($_POST['create'])) {
    $stmt = $pdo->prepare('INSERT INTO users (email, password, name, level) VALUES (?,?,?,?)');
    $lvl = min((int)$_POST['level'], 99);
    $stmt->execute([
        $_POST['email'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['name'],
        $lvl
    ]);
}

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $level = min((int)$_POST['level'], 99);
    $stmt = $pdo->prepare('UPDATE users SET email=?, name=?, level=? WHERE id=? AND level<100');
    $stmt->execute([$_POST['email'], $_POST['name'], $level, $id]);
}

if (isset($_POST['delete'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare('DELETE FROM users WHERE id=? AND level<100');
    $stmt->execute([$id]);
}

$stmt = $pdo->query('SELECT * FROM users WHERE level<100');
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Usuarios LV100</title>
</head>
<body>
    <h1>Usuarios Nivel < 100</h1>
    <table border="1" cellpadding="5">
        <tr><th>ID</th><th>Email</th><th>Nombre</th><th>Nivel</th><th>Acciones</th></tr>
        <?php foreach ($users as $u): ?>
            <tr>
                <form method="post">
                    <td><?php echo $u['id']; ?><input type="hidden" name="id" value="<?php echo $u['id']; ?>"></td>
                    <td><input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>"></td>
                    <td><input type="text" name="name" value="<?php echo htmlspecialchars($u['name']); ?>"></td>
                    <td><input type="number" name="level" value="<?php echo $u['level']; ?>" min="1" max="99"></td>
                    <td>
                        <button name="update" value="1">Guardar</button>
                        <button name="delete" value="1" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>Crear Usuario</h2>
    <form method="post">
        <label>Email: <input type="email" name="email" required></label>
        <label>Nombre: <input type="text" name="name" required></label>
        <label>Contraseña: <input type="password" name="password" required></label>
        <label>Nivel: <input type="number" name="level" value="1" min="1" max="99"></label>
        <button name="create" value="1">Crear</button>
    </form>
</body>
</html>
