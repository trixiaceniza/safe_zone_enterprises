<?php include 'includes/header.php';
$q=fn($sql)=>$pdo->query($sql)->fetchColumn();
$cards=['Total Products'=>$q('SELECT COUNT(*) FROM products'),
'Total Stock Units'=>$q('SELECT COALESCE(SUM(quantity),0) FROM products'),
'Inventory Value'=>'₱'.number_format($q('SELECT COALESCE(SUM(quantity*price),0) FROM products'),2),
'Low Stock'=>$q('SELECT COUNT(*) FROM products WHERE quantity>0 AND quantity<=low_stock_threshold'),
'Out of Stock'=>$q('SELECT COUNT(*) FROM products WHERE quantity=0'),
"Today's Sales"=>'₱'.number_format($q('SELECT COALESCE(SUM(total),0) FROM sales WHERE DATE(created_at)=CURDATE()'),2),
'This Month'=>'₱'.number_format($q('SELECT COALESCE(SUM(total),0) FROM sales WHERE YEAR(created_at)=YEAR(CURDATE()) AND MONTH(created_at)=MONTH(CURDATE())'),2),
'Transactions'=>$q('SELECT COUNT(*) FROM sales')];
$monthly=$pdo->query("SELECT DATE_FORMAT(created_at,'%Y-%m') m,SUM(total) t FROM sales GROUP BY m ORDER BY m DESC LIMIT 12")->fetchAll();
$stock=[(int)$q('SELECT COUNT(*) FROM products WHERE quantity>low_stock_threshold'),$cards['Low Stock'],$cards['Out of Stock']];
?><h1>Dashboard</h1><div class="cards"><?php foreach($cards as $k=>$v):?><div class="card"><small><?=e($k)?></small><b><?=e($v)?></b></div><?php endforeach;?></div>
<div class="cards"><div class="card"><canvas id="c1"></canvas></div><div class="card"><canvas id="c2"></canvas></div></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>const m=<?=json_encode(array_reverse($monthly))?>;
new Chart(c1,{type:'bar',data:{labels:m.map(x=>x.m),datasets:[{label:'Monthly Sales',data:m.map(x=>+x.t),backgroundColor:'#00d4ff'}]}});
new Chart(c2,{type:'doughnut',data:{labels:['In stock','Low','Out'],datasets:[{data:<?=json_encode($stock)?>,backgroundColor:['#00d4ff','#8b5cf6','#ff4d6d']}]}});</script>
<?php include 'includes/footer.php'; ?>
