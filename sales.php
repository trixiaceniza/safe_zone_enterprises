<?php include 'includes/header.php'; $msg='';
$prods=$pdo->query('SELECT id,name,price,quantity FROM products WHERE quantity>0 ORDER BY name')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_check();
  try{ $pdo->beginTransaction(); $lines=[]; $total=0;
    foreach($_POST['pid']??[] as $i=>$pid){ $qty=(int)($_POST['qty'][$i]??0); if(!$pid||$qty<1) continue;
      $s=$pdo->prepare('SELECT * FROM products WHERE id=? FOR UPDATE'); $s->execute([$pid]); $p=$s->fetch();
      if(!$p||$p['quantity']<$qty) throw new Exception('Insufficient stock for '.($p['name']??'product'));
      $lines[]=[$p,$qty]; $total+=$p['price']*$qty; }
    if(!$lines) throw new Exception('Add at least one item.');
    $pdo->prepare('INSERT INTO sales(customer,total,user_id) VALUES(?,?,?)')->execute([trim($_POST['customer']),$total,$_SESSION['uid']]);
    $sid=$pdo->lastInsertId();
    foreach($lines as [$p,$qty]){
      $pdo->prepare('INSERT INTO sale_items(sale_id,product_id,qty,price) VALUES(?,?,?,?)')->execute([$sid,$p['id'],$qty,$p['price']]);
      $pdo->prepare('UPDATE products SET quantity=quantity-? WHERE id=?')->execute([$qty,$p['id']]);
      $pdo->prepare("INSERT INTO stock_movements(product_id,change_qty,reason,user_id) VALUES(?,?,'sale',?)")->execute([$p['id'],-$qty,$_SESSION['uid']]); }
    $pdo->commit(); header("Location: receipt.php?id=$sid"); exit;
  }catch(Exception $e){ $pdo->rollBack(); $msg=$e->getMessage(); } }
?><h1>Manage Sales</h1><?php if($msg):?><div class="err"><?=e($msg)?></div><?php endif;?>
<form method="post" class="card frm"><?=csrf_field()?><input name="customer" placeholder="Customer name">
<?php for($i=0;$i<4;$i++):?><select name="pid[]"><option value="">— product —</option><?php foreach($prods as $p):?><option value="<?=$p['id']?>"><?=e($p['name'])?> (₱<?=number_format($p['price'],2)?>, stock <?=$p['quantity']?>)</option><?php endforeach;?></select>
<input type="number" name="qty[]" min="1" placeholder="Qty"><?php endfor;?><button class="btn">Record Sale</button></form>
<?php include 'includes/footer.php'; ?>
