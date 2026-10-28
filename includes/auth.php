<?php
session_start();
require_once __DIR__.'/../config/database.php';
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function require_login(){ if(empty($_SESSION['uid'])){ header('Location: login.php'); exit; } }
function csrf(){ if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_field(){ return '<input type="hidden" name="csrf" value="'.csrf().'">'; }
function csrf_check(){ if(!hash_equals($_SESSION['csrf']??'', $_POST['csrf']??'')) die('Invalid request.'); }
