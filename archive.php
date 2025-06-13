<?php
require_once __DIR__.'/auth.php';
$pdo = get_db_connection();
$page = max(1,(int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page-1)*$perPage;
$total = $pdo->query('SELECT COUNT(*) FROM bulletin_notes WHERE deleted=0')->fetchColumn();
$notes = $pdo->prepare('SELECT n.*, u.name, u.level FROM bulletin_notes n JOIN users u ON u.id=n.author_id WHERE n.deleted=0 ORDER BY COALESCE(n.boost_date,n.post_date) DESC LIMIT ? OFFSET ?');
$notes->bindValue(1,$perPage,PDO::PARAM_INT);
$notes->bindValue(2,$offset,PDO::PARAM_INT);
$notes->execute();
$list = $notes->fetchAll();
$pages = ceil($total/$perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Archive</title>
</head>
<body>
<h1>Archive</h1>
<a href="board.php">Back to the board</a> | <a href="search.php">Search</a>
<ul>
<?php foreach($list as $n){ ?>
<li><a href="#" onclick="openView(<?php echo $n['id']; ?>);return false;"><?php echo htmlspecialchars($n['title']);?></a></li>
<?php } ?>
</ul>
<div>
<?php for($i=1;$i<=$pages;$i++){ echo '<a href="?page='.$i.'">'.$i.'</a> '; } ?>
</div>
<?php include __DIR__.'/overlay.inc.php'; ?>
</body>
</html>
