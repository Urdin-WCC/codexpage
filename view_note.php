<?php
require_once __DIR__.'/auth.php';
$pdo = get_db_connection();
$id = (int)($_GET['id'] ?? 0);
$note = $pdo->prepare("SELECT n.*, u.name, u.level FROM bulletin_notes n JOIN users u ON u.id=n.author_id WHERE n.id=?");
$note->execute([$id]);
$n = $note->fetch();
if(!$n){ echo 'Note not found'; exit; }
$size = 14 + floor(($n['level'] ?? 0)/10);
?>
<div style="background:<?php echo htmlspecialchars($n['bg_color']);?>;color:<?php echo htmlspecialchars($n['text_color']);?>;font-family:<?php echo $n['font'];?>;font-size:<?php echo $size;?>px;padding:10px;">
<p style="font-size:smaller;">
<?php if($n['boost_date']){ ?>
<span style="text-decoration:line-through;"><?php echo htmlspecialchars($n['post_date']);?></span>
<span style="color:darkred;"> <?php echo htmlspecialchars($n['boost_date']);?></span>
<?php } else { echo htmlspecialchars($n['post_date']); } ?>
</p>
<h3 style="margin:0;font-weight:bold;font-size:<?php echo $size+2;?>px;"><?php echo htmlspecialchars($n['title']);?></h3>
<p><?php echo nl2br(htmlspecialchars($n['content']));?></p>
<p style="opacity:0.7;"><?php echo htmlspecialchars($n['hashtags']);?></p>
<p style="text-align:right;font-style:italic;font-size:smaller;opacity:0.9;"><?php echo htmlspecialchars($n['name']);?></p>
</div>
<div style="margin-top:10px;">
<button onclick="document.getElementById('viewOverlay').style.display='none'">Close</button>
<?php if(get_current_user()){ ?>
<form method="post" action="note_action.php" style="display:inline">
<input type="hidden" name="id" value="<?php echo $n['id']; ?>">
<button name="boost">Boost</button>
</form>
<?php }
if($user && ($user['id']==$n['author_id'] && $user['level']>50 || $user['level']>70)){ ?>
<form method="post" action="note_action.php" style="display:inline">
<input type="hidden" name="id" value="<?php echo $n['id']; ?>">
<button name="toggle_pin"><?php echo $n['pinned']? 'Unpin':'Pin'; ?></button>
</form>
<?php }
if($user){ ?>
<button onclick="navigator.clipboard.writeText(document.getElementById('viewContent').innerText)">Copy</button>
<?php }
if($user && ($user['id']==$n['author_id'] || $user['level']>80)){ ?>
<form method="post" action="note_action.php" style="display:inline" onsubmit="return confirm('Delete note?')">
<input type="hidden" name="id" value="<?php echo $n['id']; ?>">
<button name="delete">Delete</button>
</form>
<?php } ?>
</div>
