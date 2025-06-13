<?php
require_once __DIR__.'/auth.php';
$pdo = get_db_connection();
$user = get_current_user();

// Handle create note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_note'])) {
    if (!$user) { header('Location: login.php'); exit; }
    $title = substr(trim($_POST['title'] ?? ''),0,60);
    $content = substr(trim($_POST['content'] ?? ''),0,350);
    $hashtags = trim($_POST['hashtags'] ?? '');
    $hashtags = preg_replace('/\s+/', ' ', $hashtags);
    if ($title && $content && $hashtags) {
        $fonts = ['"Comic Sans MS"','"Courier New"','Georgia','Verdana','Arial'];
        $font = $fonts[array_rand($fonts)];
        $text_colors = ['#111','#222','#333','#444'];
        $bg_colors = ['#ffff99','#fffdc4','#f9f9f9','#eaffd0'];
        $text_color = $text_colors[array_rand($text_colors)];
        $bg_color = $bg_colors[array_rand($bg_colors)];
        $stmt = $pdo->prepare('INSERT INTO bulletin_notes (author_id,title,content,hashtags,font,text_color,bg_color) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([
            $user['id'],$title,
            $content,
            $hashtags,
            $font,
            $text_color,
            $bg_color
        ]);
        header('Location: board.php');
        exit;
    }
}

$pinned = $pdo->query("SELECT n.*, u.name, u.level FROM bulletin_notes n JOIN users u ON u.id=n.author_id WHERE n.deleted=0 AND n.pinned=1 ORDER BY COALESCE(n.boost_date,n.post_date) DESC LIMIT 10")->fetchAll();
$unpinned = $pdo->query("SELECT n.*, u.name, u.level FROM bulletin_notes n JOIN users u ON u.id=n.author_id WHERE n.deleted=0 AND n.pinned=0 ORDER BY COALESCE(n.boost_date,n.post_date) DESC LIMIT 20")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bulletin Board</title>
<style>
body{font-family:Arial, sans-serif;background:#f4f4f4;margin:0;padding:20px;}
header{display:flex;justify-content:space-between;align-items:center;}
#board{display:flex;flex-wrap:wrap;gap:10px;margin-top:20px;}
.note{padding:10px;width:180px;min-height:100px;box-shadow:0 0 5px rgba(0,0,0,0.3);position:relative;cursor:pointer;}
.note small{display:block;font-size:12px;}
.overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:none;align-items:center;justify-content:center;}
#newNoteOverlay,.viewOverlay{display:none;}
.overlay .modal{background:#fff;padding:20px;max-width:400px;width:100%;}
.modal label{display:block;margin-bottom:10px;}
</style>
</head>
<body>
<header>
<h1>Bulletin Board</h1>
<div>
<button onclick="document.getElementById('newNoteOverlay').style.display='flex'">Post note</button>
<a href="search.php">Search note</a>
</div>
</header>
<div id="board">
<?php
function render_note($n){
    $rotate = rand(-5,5);
    $size = 14 + floor(($n['level'] ?? 0)/10);
    echo '<div class="note" style="background:'.htmlspecialchars($n['bg_color']).';color:'.htmlspecialchars($n['text_color']).';font-family:'.$n['font'].';transform:rotate('.$rotate.'deg);font-size:'.$size.'px" onclick="openView('.$n['id'].')">';
    echo '<small>'.htmlspecialchars($n['boost_date'] ?: $n['post_date']).'</small>';
    echo '<strong>'.htmlspecialchars($n['title']).'</strong><br>';
    echo '<small>'.htmlspecialchars($n['name']).'</small>';
    echo '</div>';
}
foreach($pinned as $n) render_note($n);
foreach($unpinned as $n) render_note($n);
?>
</div>
<div><a href="archive.php">Go to archive</a></div>

<div class="overlay" id="newNoteOverlay">
<div class="modal">
<form method="post">
<input type="hidden" name="create_note" value="1">
<label>Title (max 60)<br><input type="text" name="title" maxlength="60" required></label>
<label>Content (max 350)<br><textarea name="content" maxlength="350" required></textarea></label>
<label>Hashtags (1-5)<br><input type="text" name="hashtags" required></label>
<button type="submit">Post</button>
<button type="button" onclick="document.getElementById('newNoteOverlay').style.display='none'">Cancel</button>
</form>
</div>
</div>
<div class="overlay" id="viewOverlay"><div class="modal" id="viewContent"></div></div>
<script>
function openView(id){
    fetch('view_note.php?id='+id).then(r=>r.text()).then(html=>{
        document.getElementById('viewContent').innerHTML=html;
        document.getElementById('viewOverlay').style.display='flex';
    });
}
document.getElementById('viewOverlay').addEventListener('click',e=>{if(e.target.id==='viewOverlay'){e.target.style.display='none';}});
</script>
</body>
</html>
