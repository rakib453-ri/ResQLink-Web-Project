<?php
require_once __DIR__ . "/../includes/auth.php"; require_role("seeker");
require_once __DIR__ . "/../config/database.php";
$uid=$_SESSION["user_id"];$edit=null;
if(isset($_GET["delete"])){$id=(int)$_GET["delete"];$s=$conn->prepare("DELETE FROM emergency_contacts WHERE id=? AND seeker_user_id=?");$s->bind_param("ii",$id,$uid);$s->execute();flash("success","Emergency contact deleted.");header("Location: contacts.php");exit;}
if(isset($_GET["edit"])){$id=(int)$_GET["edit"];$s=$conn->prepare("SELECT * FROM emergency_contacts WHERE id=? AND seeker_user_id=?");$s->bind_param("ii",$id,$uid);$s->execute();$edit=$s->get_result()->fetch_assoc();}
if($_SERVER["REQUEST_METHOD"]==="POST"){$id=(int)($_POST["id"]??0);$name=trim($_POST["name"]??"");$phone=trim($_POST["phone"]??"");$email=trim($_POST["email"]??"");$rel=trim($_POST["relationship"]??"");
if($name&&$phone&&$rel){if($id){$s=$conn->prepare("UPDATE emergency_contacts SET name=?,phone=?,email=?,relationship=? WHERE id=? AND seeker_user_id=?");$s->bind_param("ssssii",$name,$phone,$email,$rel,$id,$uid);}else{$s=$conn->prepare("INSERT INTO emergency_contacts(seeker_user_id,name,phone,email,relationship) VALUES(?,?,?,?,?)");$s->bind_param("issss",$uid,$name,$phone,$email,$rel);}$s->execute();flash("success",$id?"Contact updated.":"Contact added.");header("Location: contacts.php");exit;}}
$s=$conn->prepare("SELECT * FROM emergency_contacts WHERE seeker_user_id=? ORDER BY id DESC");$s->bind_param("i",$uid);$s->execute();$rows=$s->get_result();
$pageTitle="Emergency Contacts";include __DIR__ . "/../includes/header.php";?>
<div class="layout"><?php include __DIR__ . "/../includes/sidebar.php";?><section class="content"><h2>Emergency Contact Management</h2><?php show_flash();?>
<div class="section"><h3><?=$edit?"Edit Contact":"Add Contact"?></h3><form method="post" class="form-grid"><input type="hidden" name="id" value="<?=e($edit['id']??'')?>">
<div class="form-group"><label>Name</label><input name="name" required value="<?=e($edit['name']??'')?>"></div><div class="form-group"><label>Phone</label><input name="phone" required value="<?=e($edit['phone']??'')?>"></div>
<div class="form-group"><label>Email</label><input type="email" name="email" value="<?=e($edit['email']??'')?>"></div><div class="form-group"><label>Relationship</label><input name="relationship" required value="<?=e($edit['relationship']??'')?>"></div>
<div class="form-group full"><button class="btn"><?=$edit?"Update":"Add"?></button></div></form></div>
<div class="table-wrap"><table><tr><th>Name</th><th>Phone</th><th>Email</th><th>Relationship</th><th>Actions</th></tr><?php while($r=$rows->fetch_assoc()):?><tr><td><?=e($r['name'])?></td><td><?=e($r['phone'])?></td><td><?=e($r['email'])?></td><td><?=e($r['relationship'])?></td><td class="actions"><a class="btn small" href="?edit=<?=$r['id']?>">Edit</a><a class="btn small danger" data-confirm="Delete this contact?" href="?delete=<?=$r['id']?>">Delete</a></td></tr><?php endwhile;?></table></div>
</section></div><?php include __DIR__ . "/../includes/footer.php";?>