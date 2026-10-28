<?php include 'includes/header.php';
$s=$pdo->prepare('SELECT * FROM sales WHERE id=?'); $s->execute([(int)$_GET['id']]); $sale=$s->fetch() or die('Not found');
$i=$pdo->prepare('SELECT si.*,p.name FROM sale_items si JOIN products p ON p.id=si.product_id WHERE sale_id=?'); $i->execute([$sale['id']]);
?><div class="card" id="rc"><h2>SAFE ZONE ENTERPRISES</h2><p>Receipt #<?=$sale['id']?> — <?=e($sale['created_at'])?><br>Customer: <?=e($sale['customer']?:'Walk-in')?></p>
<table><tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
<?php foreach($i as $r):?><tr><td><?=e($r['name'])?></td><td><?=$r['qty']?></td><td><?=number_format($r['price'],2)?></td><td><?=number_format($r['qty']*$r['price'],2)?></td></tr><?php endforeach;?></table>
<h3>Total: ₱<?=number_format($sale['total'],2)?></h3></div><button class="btn" onclick="print()">Print</button>
<?php include 'includes/footer.php'; ?>
