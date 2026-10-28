<?php require 'includes/auth.php'; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_check();
  $email=trim($_POST['email']??''); $pw=$_POST['password']??'';
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)||$pw==='') $err='Enter a valid email and password.';
  else { $s=$pdo->prepare('SELECT * FROM users WHERE email=?'); $s->execute([$email]); $u=$s->fetch();
    if($u && password_verify($pw,$u['password'])){ session_regenerate_id(true); $_SESSION['uid']=$u['id']; $_SESSION['name']=$u['name']; header('Location: dashboard.php'); exit; }
    $err='Invalid credentials.'; } }
?><!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<canvas id="bg"></canvas><div class="grid"></div>
<form method="post" class="card login"><h2>SAFE ZONE ENTERPRISES</h2><p>Home Appliances Inventory System</p>
<?php if($err):?><div class="err"><?=e($err)?></div><?php endif; echo csrf_field(); ?>
<input type="email" name="email" placeholder="Email" required>
<input type="password" id="pw" name="password" placeholder="Password" required>
<label><input type="checkbox" onclick="pw.type=this.checked?'text':'password'"> Show password</label>
<button class="btn">Login</button></form><script src="assets/js/app.js"></script></body></html>
