<?php require_once __DIR__.'/auth.php'; require_login(); $cur=basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Safe Zone Enterprises</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<canvas id="bg"></canvas><div class="grid"></div>
<aside id="side"><h2>SAFE ZONE</h2><nav>
<?php foreach(['dashboard.php'=>'📊 Dashboard','products.php'=>'📦 Product Inventory','sales.php'=>'🛒 Manage Sales','logout.php'=>'🚪 Logout'] as $f=>$l): ?>
<a href="<?=$f?>" class="<?=$cur===$f?'on':''?>"><?=$l?></a><?php endforeach; ?></nav></aside>
<main><button id="tg" onclick="document.body.classList.toggle('collapsed')">☰</button>
