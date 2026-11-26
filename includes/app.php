<?php
declare(strict_types=1);
session_start();

const APP_NAME='Électronique';
const CURRENCY='XOF';
const MAX_UPLOAD_BYTES=5242880;

function env_value(string $k,?string $d=null):?string{$v=getenv($k);return $v===false?$d:$v;}
function db():PDO{static $pdo;if($pdo)return $pdo;$pdo=new PDO('mysql:host='.env_value('DB_HOST','127.0.0.1').';dbname='.env_value('DB_NAME','boutique_electronique').';charset=utf8mb4',env_value('DB_USER','root'),env_value('DB_PASS',''),[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);return $pdo;}
function e(?string $v):string{return htmlspecialchars($v??'',ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function csrf_token():string{if(empty($_SESSION['_csrf']))$_SESSION['_csrf']=bin2hex(random_bytes(32));return $_SESSION['_csrf'];}
function csrf_field():string{return '<input type="hidden" name="_csrf" value="'.e(csrf_token()).'">';}
function verify_csrf():void{$s=$_POST['_csrf']??'';if(!hash_equals(csrf_token(),is_string($s)?$s:'')){http_response_code(419);exit('Session expirée.');}}
function money(int|float|string $n):string{return number_format((float)$n,0,',',' ').' '.CURRENCY;}
function redirect(string $p):never{header('Location: '.$p);exit;}
function flash(string $m,string $t='success'):void{$_SESSION['_flash']=[$m,$t];}
function take_flash():?array{$f=$_SESSION['_flash']??null;unset($_SESSION['_flash']);return $f?['message'=>$f[0],'type'=>$f[1]]:null;}
function cart_count():int{return array_sum(array_map('intval',$_SESSION['cart']??[]));}
function product_image(?string $p):string{return $p?:'assets/img/product-placeholder.svg';}
function render_header(string $title,string $active=''):void{$n=take_flash();?><!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#635bff"><title><?=e($title)?> · <?=APP_NAME?></title><link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml"><link rel="stylesheet" href="assets/css/style.css"><script defer src="assets/js/app.js"></script></head><body><header class="site-header"><div class="container header-inner"><a class="brand" href="index.php"><span class="brand-mark">E</span><span>Électronique<small>La technologie, simplement.</small></span></a><button class="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false">☰</button><nav class="main-nav"><a class="<?=$active==='home'?'active':''?>" href="index.php">Accueil</a><a href="index.php#produits">Boutique</a><a href="index.php#avantages">Nos engagements</a></nav><a class="cart-link" href="cart.php">Panier <span><?=cart_count()?> </span></a></div></header><main><?php if($n):?><div class="container"><div class="notice <?=e($n['type'])?>"><?=e($n['message'])?></div></div><?php endif;?><?php }
function render_footer():void{?></main><footer class="site-footer"><div class="container footer-inner"><a class="brand footer-brand" href="index.php"><span class="brand-mark">E</span><span>Électronique<small>Votre boutique tech de confiance</small></span></a><p>© <?=date('Y')?> Électronique. Tous droits réservés.</p><a href="admin/login.php">Espace administrateur</a></div></footer></body></html><?php}
