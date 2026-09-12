<?php
require_once __DIR__ . "/../includes/auth.php"; require_role("seeker");
require_once __DIR__ . "/../config/database.php";
$uid=$_SESSION["user_id"]; $edit=null;
if(isset($_GET["delete"])){ $id=(int)$_GET["delete"]; $s=$conn->prepare("DELETE FROM shelter_requests WHERE id=? AND seeker_user_id=?");$s->bind_param("ii",$id,$uid);$s->execute();flash("success","Request deleted.");header("Location: requests.php");exit;}
if(isset($_GET["edit"])){ $id=(int)$_GET["edit"]; $s=$conn->prepare("SELECT * FROM shelter_requests WHERE id=? AND seeker_user_id=?");$s->bind_param("ii",$id,$uid);$s->execute();$edit=$s->get_result()->fetch_assoc();}
if($_SERVER["REQUEST_METHOD"]==="POST"){
 $id=(int)($_POST["id"]??0);$location=trim($_POST["location"]??"");$people=(int)($_POST["people_count"]??1);$type=trim($_POST["emergency_type"]??"");$needs=trim($_POST["special_needs"]??"");$desc=trim($_POST["description"]??"");
 if($location && $people>0 && $type){
  if($id){$s=$conn->prepare("UPDATE shelter_requests SET location=?,people_count=?,emergency_type=?,special_needs=?,description=? WHERE id=? AND seeker_user_id=?");$s->bind_param("sisssii",$location,$people,$type,$needs,$desc,$id,$uid);}
  else{$s=$conn->prepare("INSERT INTO shelter_requests(seeker_user_id,location,people_count,emergency_type,special_needs,description) VALUES(?,?,?,?,?,?)");$s->bind_param("isisss",$uid,$location,$people,$type,$needs,$desc);}
  $s->execute();flash("success",$id?"Request updated.":"Request created.");header("Location: requests.php");exit;
 }
}
$s=$conn->prepare("SELECT r.*,s.name shelter_name FROM shelter_requests r LEFT JOIN shelters s ON s.id=r.assigned_shelter_id WHERE r.seeker_user_id=? ORDER BY r.id DESC");$s->bind_param("i",$uid);$s->execute();$rows=$s->get_result();
$pageTitle="Shelter Requests";include __DIR__ . "/../includes/header.php";?>
<div class="layout"><?php include __DIR__ . "/../includes/sidebar.php";?><section class="content"><h2>Shelter Request Management</h2><?php show_flash();?>
<div class="section"><h3><?=$edit?"Edit Request":"Create New Request"?></h3><form method="post" class="form-grid">
<input type="hidden" name="id" value="<?=e($edit['id']??'')?>">
<div class="form-group"><label>Location</label><input name="location" required value="<?=e($edit['location']??'')?>"></div>
<div class="form-group"><label>People Count</label><input type="number" min="1" name="people_count" required value="<?=e($edit['people_count']??1)?>"></div>
<div class="form-group"><label>Emergency Type</label><select name="emergency_type" required><?php foreach(['Flood','Cyclone','Fire','Other'] as $x):?><option <?=($edit['emergency_type']??'')===$x?'selected':''?>><?=e($x)?></option><?php endforeach;?></select></div>
<div class="form-group"><label>Special Needs</label><input name="special_needs" value="<?=e($edit['special_needs']??'')?>"></div>
<div class="form-group full"><label>Description</label><textarea name="description"><?=e($edit['description']??'')?></textarea></div>
<div class="form-group full"><button class="btn"><?=$edit?"Update":"Create"?></button><?php if($edit):?> <a class="btn secondary" href="requests.php">Cancel</a><?php endif;?></div>
</form></div>
<div class="table-wrap"><table><tr><th>ID</th><th>Location</th><th>People</th><th>Type</th><th>Status</th><th>Assigned Shelter</th><th>Actions</th></tr>
<?php while($r=$rows->fetch_assoc()):?><tr><td><?=$r['id']?></td><td><?=e($r['location'])?></td><td><?=$r['people_count']?></td><td><?=e($r['emergency_type'])?></td><td><span class="badge"><?=e($r['status'])?></span></td><td><?=e($r['shelter_name']??'Not assigned')?></td><td class="actions"><a class="btn small" href="?edit=<?=$r['id']?>">Edit</a><a class="btn small danger" data-confirm="Delete this request?" href="?delete=<?=$r['id']?>">Delete</a></td></tr><?php endwhile;?></table></div>
</section></div><?php include __DIR__ . "/../includes/footer.php";?>