<?php
require_once __DIR__.'/auth.php';
$pdo = get_db_connection();
$query = trim($_GET['q'] ?? '');
$results = [];
if($query){
    if(strpos($query,'#')===0){
        $stmt = $pdo->prepare("SELECT n.*, u.name, u.level FROM bulletin_notes n JOIN users u ON u.id=n.author_id WHERE n.deleted=0 AND n.hashtags LIKE ? ORDER BY COALESCE(n.boost_date,n.post_date) DESC");
        $stmt->execute(['%'.$query.'%']);
    } else {
        $stmt = $pdo->prepare("SELECT n.*, u.name, u.level FROM bulletin_notes n JOIN users u ON u.id=n.author_id WHERE n.deleted=0 AND u.name LIKE ? ORDER BY COALESCE(n.boost_date,n.post_date) DESC");
        $stmt->execute(['%'.$query.'%']);
    }
    $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Notes</title>
</head>
<body>
<h1>Search Notes</h1>
<form method="get">
<input type="text" name="q" value="<?php echo htmlspecialchars($query);?>" placeholder="#hashtag or author">
<button type="submit">Search</button>
</form>
<ul>
<?php foreach($results as $n){ ?>
<li><a href="#" onclick="openView(<?php echo $n['id']; ?>);return false;"><?php echo htmlspecialchars($n['title']);?></a></li>
<?php } ?>
</ul>
<?php include __DIR__.'/overlay.inc.php'; ?>
</body>
</html>
