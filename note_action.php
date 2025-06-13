<?php
require_once __DIR__.'/auth.php';
require_login();
$pdo = get_db_connection();
$id = (int)($_POST['id'] ?? 0);
$noteStmt = $pdo->prepare('SELECT * FROM bulletin_notes WHERE id=?');
$noteStmt->execute([$id]);
$note = $noteStmt->fetch();
if(!$note){ die('Not found'); }
$user = get_current_user();
if(isset($_POST['boost'])){
    $pdo->prepare('UPDATE bulletin_notes SET boost_date=NOW() WHERE id=?')->execute([$id]);
}
if(isset($_POST['toggle_pin']) && (($user['id']==$note['author_id'] && $user['level']>50) || $user['level']>70)){
    $new = $note['pinned']?0:1;
    $pdo->prepare('UPDATE bulletin_notes SET pinned=? WHERE id=?')->execute([$new,$id]);
}
if(isset($_POST['delete']) && ($user['id']==$note['author_id'] || $user['level']>80)){
    $pdo->prepare('UPDATE bulletin_notes SET deleted=1 WHERE id=?')->execute([$id]);
}
header('Location: board.php');
