<?php
require_once __DIR__ . "/auth.php";
$pageTitle = $pageTitle ?? "ResQLink";
$authPage = $authPage ?? false;
function role_label($r){return $r==='seeker'?'Shelter Seeker':($r==='volunteer'?'Volunteer':($r==='admin'?'Administrator':ucfirst($r)));}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=clean($pageTitle)?> | ResQLink</title><link rel="stylesheet" href="/ResQLink/assets/css/style.css?v=final3"></head><body>
<?php if(!$authPage): ?><header class="topbar">
<a class="brand" href="/ResQLink/index.php">ResQLink</a><div class="page-title"><?=clean($pageTitle)?></div>
<div class="top-right"><?php if(isset($_SESSION['user_id'])): ?>
<span class="icon-btn" title="Notifications"><svg viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg></span>
<span class="profile-chip"><span class="avatar"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></span>
<span class="profile-copy"><div class="profile-name"><?=clean($_SESSION['name']??role_label($_SESSION['role']??''))?></div><div class="profile-role"><?=clean(role_label($_SESSION['role']??''))?></div></span>
<svg class="chevron" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>
<?php endif;?></div></header><?php endif;?><main class="<?=$authPage?'':'app-shell'?>">