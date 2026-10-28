<?php include 'includes/header.php'; $msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_check();
  try{ $pdo->beginTransaction();
    $cat=trim($_POST['category']??''); $cid=null;
    if($cat!==''){ $pdo->prepare('INSERT IGNORE INTO categories(name) VALUES(?)')->execute([$cat]);
      $s=$pdo->prepare('SELECT id FROM categories WHERE name=?'); $s->execute([$cat]); $cid=$s->fetchColumn(); }
    $qty=max(0,(int)$_POST['quantity']);
    $pdo->prepare('INSERT INTO products(sku,name,category_id,price,quantity,low_stock_threshold) VALUES(?,?,?,?,?,?)')
      ->execute([trim($_POST['sku']),trim($_POST['name']),$cid,max(0,(float)$_POST['price']),$qty,max(0,(int)$_POST['threshold'])]);
    $pid=$pdo->lastInsertId();
    if($qty>0) $pdo->prepare("INSERT INTO stock_movements(product_id,change_qty,reason,user_id) VALUES(?,?,'opening stock',?)")->execute([$pid,$qty,$_SESSION['uid']]);
    $pdo->commit(); $msg='Product added.';
  }catch(Exception $e){ $pdo->rollBack(); $msg='Could not save (duplicate SKU?).'; } }
$search=trim($_GET['q']??''); $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.name LIKE ? OR p.sku LIKE ? ORDER BY p.name");
$s->execute(["%$search%","%$search%"]); $rows=$s->fetchAll();
?><h1>Product Inventory</h1><?php if($msg):?><div class="err"><?=e($msg)?></div><?php endif;?>
<form method="post" class="card frm"><?=csrf_field()?><input name="sku" placeholder="SKU" required><input name="name" placeholder="Product name" required>
<input name="category" placeholder="Category (e.g. Refrigerator)"><input name="price" type="number" step="0.01" placeholder="Price" required>
<input name="quantity" type="number" placeholder="Opening qty" value="0"><input name="threshold" type="number" placeholder="Low-stock threshold" value="5"><button class="btn">Add Product</button></form>
<form class="frm"><input name="q" placeholder="Search..." value="<?=e($search)?>"><button class="btn">Search</button></form>
<div class="card"><table><tr><th>SKU</th><th>Name</th><th>Category</th><th>Price</th><th>Qty</th><th>Status</th></tr>
<?php foreach($rows as $r): $st=$r['quantity']==0?'Out':($r['quantity']<=$r['low_stock_threshold']?'Low':'OK');?>
<tr><td><?=e($r['sku'])?></td><td><?=e($r['name'])?></td><td><?=e($r['cat'])?></td><td>₱<?=number_format($r['price'],2)?></td><td><?=$r['quantity']?></td><td><?=$st?></td></tr><?php endforeach;?></table></div>
<?php include 'includes/footer.php'; ?>
